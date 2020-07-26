<?php

namespace Framework;

trait ResponseTrait
{
    /**
     * Status code of response
     *
     * @var int $statusCode
     */
    protected $statusCode = 200;

    /**
     * Message of response
     *
     * @var string $message
     */
    protected $message = '';

    /**
     * Status of response
     *
     * @var string $status
     */
    protected $status = 'success';

    /**
     * Setter for statusCode
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Setter for statusCode
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Setter for status
     *
     * @param boolean $status Value to set
     *
     * @return self
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    public function respond(): Response
    {
        return response()->setStatus($this->statusCode)->json([
            'status'    => $this->status,
            'code'      => $this->statusCode,
            'data'      => null,
            'message'   => $this->message
        ], JSON_NUMERIC_CHECK);
    }

    protected function responseEmpty(): Response
    {
        return $this->setStatus('error')
            ->setStatusCode(400)
            ->setMessage('empty request')
            ->respond();
    }

    protected function responseNotFound()
    {
        return $this->setStatus('error')
            ->setStatusCode(404)
            ->setMessage('not found')
            ->respond();
    }

    public function responseWithArray($data): Response
    {
        return response()->setStatus($this->statusCode)->json([
            'status'    => $this->status,
            'code'      => $this->statusCode,
            'data'      => $data,
            'message'   => $this->message
        ], JSON_NUMERIC_CHECK);
    }

    protected function respondWithObject(AbstractModel $item, $callbackResource = null): Response
    {
        return response()->setStatus($this->statusCode)->json([
            'status'    => $this->status,
            'code'      => $this->statusCode,
            'data'      => $item->toArray(), // TODO implements callback
            'message'   => $this->message
        ], JSON_NUMERIC_CHECK);
    }

    protected function responseDeleted()
    {
        return $this->setStatus('success')
            ->setStatusCode(204)
            ->setMessage('deleted successfully')
            ->respond();
    }

    protected function responseValidation(array $validatorResponse)
    {
        return $this->setStatus('error')
            ->setStatusCode(400)
            ->setMessage('validation error')
            ->responseWithArray($validatorResponse);
    }
    



}
