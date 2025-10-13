<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingPromptsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('training_prompts')->insert([
            [
                'title' => 'Prompt Short',
                'description' => 'Generate a cybersecurity training text regarding the -threat attack, customized based on the psychological characteristics provided in this JSON. Use a friendly tone and include simple explanations, practical examples, and concrete tips to recognize and prevent this type of attack.',
                'pros' => 'Adaptability: Can be used for any type of attack simply by replacing the variable.  
Simplicity: Remains quick to understand and apply.  
Flexibility: Allows room for the model’s creative interpretation.',
                'cons' => 'Generic output: The lack of details may result in less structured or specific outcomes.',
                'type' => 'Text',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Prompt Medium',
                'description' => 'Generate a cybersecurity training text regarding the -threat attack, based on the psychological characteristics provided in this JSON. The text should include:  
- A simple and clear explanation of -threat.  
- Practical techniques to recognize signs of this type of attack.  
- Exercises to improve daily vigilance regarding -threat.  
- Tips to enhance digital awareness and prevent such threats. The tone should be reassuring and accessible, with concrete and applicable examples.',
                'pros' => 'Completeness: Provides clear guidelines for a more structured outcome.  
Focus: The explicit reference to -threat ensures the text remains targeted.',
                'cons' => 'Less flexibility: Compared to the short version, it is more rigid.  
Moderate complexity: Requires the model to have a higher level of understanding to meet all criteria.',
                'type' => 'Text',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Prompt Detailed',
                'description' => 'Generate a cybersecurity training text regarding the -threat attack, customized based on the psychological characteristics provided in this JSON. The text should include:  
- A clear and accessible explanation of -threat, focusing on how this attack manifests.  
- A detailed list of techniques to recognize signs of -threat, supported by practical examples based on the psychological vulnerabilities identified in the JSON.  
- Practical exercises to improve daily vigilance, such as carefully checking specific details relevant to -threat.  
            
Specific suggestions to increase digital awareness, focusing on proactive behaviors to prevent -threat.  
Guidelines to integrate the suggested strategies into work or personal routines, ensuring greater protection against -threat. The tone should be friendly, reassuring, and accessible, avoiding complex technical terms and presenting concrete examples.',
                'pros' => 'Highly detailed: Ideal for scenarios requiring comprehensive and high-quality content.  
Specific focus: The explicit reference to -threat ensures targeted and relevant content.  
Personalization: Perfectly adapts to contexts where the audience has specific psychological vulnerabilities.',
                'cons' => 'High complexity: More challenging for the model to generate a complete and coherent response.  
Rigidity: Reduces the model’s creativity in favor of a structured approach.',
                'type' => 'Text',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
