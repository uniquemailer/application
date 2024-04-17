<?php

namespace Database\Factories;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Template::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'subject' => $this->faker->sentence,
            'filename' => $this->faker->word,
            'placeholders' => ['product_name'],
            'sensitive_placeholders' => ['customer_name', 'customer_email'],
            'html_template' => '<h3>{{customer_name}}</h3> This is the product {{product_name}}. <p>This email is {{customer_email}}</p>',
            'text_template' => '{{customer_name}} This is the product {{product_name}. This email is {{customer_email}}'
        ];
    }
}
