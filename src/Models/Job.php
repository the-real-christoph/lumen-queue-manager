<?php

namespace LumenQueueManager\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Job
 * @package App\Models
 *
 * @property int $attempts
 * @property int $reserved_at
 * @property int $available_at
 * @property int $created_at
 */
class Job extends Model
{

    protected $payloadDecoded = null;

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = config('queue-manager.jobs_table', 'jobs');
        parent::__construct($attributes);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'queue', 'payload', 'attempts', 'reserved_at', 'available_at', 'created_at',
    ];


    public function getPayload() : array
    {
        if($this->payloadDecoded === null) {
            $this->payloadDecoded = json_decode($this->payload, true);
        }

        return $this->payloadDecoded;
    }

    public function getDisplayName(): string
    {
        return $this->getPayload()['displayName'] ?? '';
    }

    public function getUuid()
    {
        return $this->getPayload()['uuid'];
    }

    public function getReservedAt()
    {
        return $this->reserved_at ? (new \DateTime())->setTimestamp($this->reserved_at) : null;
    }

    public function getAvailableAt()
    {
        return (new \DateTime())->setTimestamp($this->available_at);
    }

    public function getCreatedAt()
    {
        return (new \DateTime())->setTimestamp($this->created_at);
    }

    public function getStatusText()
    {
        if($this->available_at > time()) {
            return "delayed until " . $this->getAvailableAt()->format(DATE_ATOM);
        }
        return $this->getReservedAt() ? 'Reserved at ' . $this->getReservedAt()->format(DATE_ATOM) : 'Pending';
    }

}
