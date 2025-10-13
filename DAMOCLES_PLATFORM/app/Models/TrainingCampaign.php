<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCampaign extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'training_campaigns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'threat_id',
        'llm_id',
        'prompt',
        'type', // Text, Audio
        'education', // Nothing, Low, Medium, High, Maximum
        'evaluator_id',
        'state', // Draft - In bozza, Ready - Pronto, Live - Avviato, Completed - Concluso
        'expiration_date',
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
     * Define a belongsTo relationship with Threat model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function threat()
    {
        return $this->belongsTo(Threat::class, 'threat_id');
    }

    /**
     * Define a belongsTo relationship with LLM model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function llm()
    {
        return $this->belongsTo(LLM::class);
    }
}
