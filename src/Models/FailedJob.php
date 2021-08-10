<?php

namespace LumenQueueManager\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FailedJob
 * @package App\Models
 *
 * @property int $id
 * @property string $uuid
 * @property string $connection
 * @property string $queue
 * @property string $payload
 * @property string $exception
 * @property int $failed_at
 */
class FailedJob extends Model
{

    protected $payloadDecoded = null;

    public function __construct(array $attributes = [])
    {
        $this->table = config('queue-manager.failed_jobs_table', 'failed_jobs');
        parent::__construct($attributes);
    }

    public function getPayload() : array
    {
        if($this->payloadDecoded === null) {
            $this->payloadDecoded = json_decode($this->payload, true);
        }

        return $this->payloadDecoded;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getDisplayName(): string
    {
        return $this->getPayload()['displayName'] ?? '';
    }

    public function getFullExceptionText()
    {
        return $this->exception;
    }

    public function getExceptionPreviewText()
    {
        return strstr($this->exception,"\n",true);
    }

}
