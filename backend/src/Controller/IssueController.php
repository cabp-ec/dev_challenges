<?php

namespace App\Controller;

use Base;
use Exception;
use App\Http\Response;
use App\Model\Factory\MemberFactory;
use App\Service\DataValidationService;
use App\Service\StorageService;
use App\Model\Factory\IssueFactory;

class IssueController extends BaseController
{
    public function __construct(
        private IssueFactory          $issueFactory,
        private MemberFactory         $memberFactory,
        private DataValidationService $dataValidation,
        private StorageService        $storage
    )
    {
    }

    public function index(Base $f3, array $params)
    {
        $output = ['Issue does not exist'];
        $status = Response::HTTP_BAD_REQUEST;
        $code = $params['issue'] ?? null;
        $validInput = $this->dataValidation->input([$code]);

        if ($validInput && $this->storage->exists($code)) {
            $issue = $this->issueFactory->create($code);
            $issue->parse($this->storage->get($code));

            return $this->respond($issue->toArray(), Response::HTTP_OK);
        }

        $this->respond($output, $status);
    }

    /**
     * Check if an issue already exists
     *
     * @param Base $f3
     * @param array $params
     */
    public function exists(Base $f3, array $params)
    {
        $this->respond([false], 404);
    }

    /**
     * Join an issue
     *
     * @param Base $f3
     * @param array $params
     * @return Response
     */
    public function join(Base $f3, array $params): Response
    {
        $output = ['Input is invalid'];
        $status = Response::HTTP_BAD_REQUEST;
        $code = $params['issue'] ?? null;
        $body = json_decode($f3->get('BODY'), true);
        $validInput = $this->dataValidation->input([$code, $body]);

        if (true !== $validInput) {
            return $this->respond($output, $status);
        }

        try {
            if ($this->storage->exists($code)) {
                $issue = $this->issueFactory->create($code);
                $issue->parse($this->storage->get($code));
            } else {
                $issue = $this->issueFactory->create($code, true);
            }

//            $issue->setStatus('voting');
//            $issue->save();

            $member = $this->memberFactory->make($body['name']);
            $issue->addMember($member);
            $issue->save();

            $status = 200;
            $output = $issue->toArray();
//            $output = $member->toArray();
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return $this->respond($output, $status);
    }

    public function vote(Base $f3, array $params): Response
    {
        $output = ['Input is invalid'];
        $status = Response::HTTP_BAD_REQUEST;
        $code = $params['issue'] ?? null;
        $body = json_decode($f3->get('BODY'), true);
        $validInput = $this->dataValidation->input([$code, $body]);

        if (true !== $validInput) {
            return $this->respond($output, $status);
        }

        try {
            if (true !== $this->storage->exists($code)) {
                $status = 404;
                $output = [
                    'status' => $status,
                    'message' => 'Issue not found'
                ];
            }

            $issue = $this->issueFactory->create($code);
            $issue->parse($this->storage->get($code));
            $status = $issue->getStatus();

//        Reject votes when status of {:issue} is not voting.
            if ('voting' === $status) {
                $member = $this->memberFactory->make($body['name']);
                $value = (float)$body['vote'];
                $member->setValue($value);
                $member->setStatus($value > 0 ? 'voted' : 'passed');

//        Reject votes if user not joined {:issue}.
//        Reject votes if user already voted or passed.
                if (false === $issue->memberExists($member) || false === $issue->voted($member)) {
                    $status = 409;
                    $output = ['error' => 'You already voted'];
                }

                $issue->vote($member);
                $issue->save();
                $status = 200;
                return $this->respond($issue->toArray(), $status);
            }

            $status = 401;
            $output = ['error' => 'Issue is not accepting votes'];
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return $this->respond($output, $status);
    }
}
