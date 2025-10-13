<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhishingEmail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'phishing_emails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject',
        'body',
        'explanation',
        'topic_id',
        'emotional_triggers',
        'persuasions',
        'llm_id',
        'prompt',
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
     * Define a belongsTo relationship with LLM model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function llm()
    {
        return $this->belongsTo(LLM::class);
    }
}
