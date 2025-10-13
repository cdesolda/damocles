<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhishingCampaign extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'phishing_campaigns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'number_emails',
        'topic_id',
        'emotional_triggers',
        'persuasions',
        'llm_id',
        'prompt',
        'evaluator_id',
        'state', // Draft - In bozza, Ready - Pronto, Live - Avviato, Completed - Concluso
        'expiration_date',
        'timing_email'
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
        'emotional_triggers' => 'array',
        'persuasions' => 'array',
    ];

    /**
     * Define a belongsTo relationship with PhishingTopic model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function phishingTopic()
    {
        return $this->belongsTo(PhishingTopic::class, 'topic_id');
    }

    /**
     * Define a one-to-many relationship with PhishingPersuasion model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phishingPersuasions()
    {
        return $this->hasMany(PhishingPersuasion::class);
    }

    /**
     * Define a one-to-many relationship with PhishingEmotionalTrigger model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phishingEmotionalTriggers()
    {
        return $this->hasMany(PhishingEmotionalTrigger::class);
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

    /*
    /**
     * Define a one-to-many relationship with PhishingEmailPhishingCampaign model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function phishingEmailPhishingCampaign()
    {
        return $this->hasMany(PhishingEmailPhishingCampaign::class, 'phishing_campaign_id');
    }
}
