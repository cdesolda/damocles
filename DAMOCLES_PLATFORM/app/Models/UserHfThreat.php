<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHfThreat extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_hf_threats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'hf_id',
        'threat_id',
        'severityLevel'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Get the severity level for a specific user and human factor.
     *
     * This method retrieves the severity level from the UserHfThreat table
     * based on the provided user ID and human factor ID. If no record is found,
     * it returns the default value 'not measured'.
     *
     * @param int $userId The ID of the user.
     * @param int $hfId The ID of the human factor.
     * @return string The severity level if found, otherwise 'not measured'.
     */
    public static function getSeverityLevel($userId, $hfId)
    {
        $record = self::where('user_id', $userId)->where('hf_id', $hfId)->first();
        return $record ? $record->severityLevel : 'not measured';
    }

    /**
     * Define a belongsTo relationship with User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a belongsTo relationship with HumanFactor model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function humanFactor()
    {
        return $this->belongsTo(HumanFactor::class, 'hf_id');
    }

    /**
     * Define a belongsTo relationship with Threat model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function threat()
    {
        return $this->belongsTo(Threat::class, 'threat_id');
    }
}
