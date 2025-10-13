<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalTwinsCampaign extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'digital_twins_campaigns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'threat_id',
        'evaluator_id',
        'demographics',
        'human_factors', 
        'user_prompt_type',
        'user_prompt',
        'llm_id',
        'threat_prompt_type',
        'threat_prompt',
        'emails_ids',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'demographics' => 'array',
        'human_factors' => 'array',
        'emails_ids' => 'array', 
    ];

        /**
     * Define a belongsTo relationship with LLM model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function llm()
    {
        return $this->belongsTo(LLM::class);
    } 

    /**
     * Define a belongsTo relationship with Threat model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function threat()
    {
        return $this->belongsTo(Threat::class);
    } 

    /**
     * Define a one-to-many relationship with HumanFactor model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function humanFactors()
    {
        return $this->hasMany(HumanFactor::class);
    }

    /**
     * Define a one-to-one relationship with Evaluator model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function evaluator()
    {
        return $this->hasOne(User::class, 'evaluator_id');
    }
}