<?php
/**
 * Created by PhpStorm.
 * User: Cristian Benescu
 * Date: 29/01/2019
 * Time: 10:18
 */

namespace Blog\Assistants\BlogCustomUrl;

use Blog\Services\BlogService;
use IO\Services\WebstoreConfigurationService;
use Plenty\Modules\Plugin\PluginSet\Contracts\PluginSetRepositoryContract;
use Plenty\Modules\System\Contracts\WebstoreRepositoryContract;
use Plenty\Modules\Wizard\Services\WizardProvider;

/**
 * Class BlogCustomUrlAssistant
 * @package Blog\Assistants\BlogCustomUrl
 */
class BlogCustomUrlAssistant extends WizardProvider
{

    /**
     * The wizard structure
     *
     * @return array
     */
    protected function structure() {

        return [
            'title' => 'Blog Landing Page',
            'key' => 'blog-landing-page',
            'translationNamespace' => 'Blog',
            'dataSource' => 'Blog\Assistants\BlogCustomUrl\DataSource\BlogCustomUrlDataSource',
            'settingsHandlerClass' => 'Blog\Assistants\BlogCustomUrl\SettingsHandler\BlogCustomUrlSettingsHandler',
            'reloadStructure' => true,
            'createOptionIdTitle' => 'Create new setting',
            'options' => [
                'data' => [
                    'urlName'
                ],
                'pluginSetId' => [
                    'type' => 'select',
                    'defaultValue' => 1,
                    'options' => [
                        'name' => 'Plugin set',
                        'required' => true,
                        'listBoxValues' => $this->listPluginSets()
                    ]
                ],
                'languageCode' => [
                    'type' => 'select',
                    'defaultValue' => 'de',
                    'options' => [
                        'name' => 'Language',
                        'required' => true,
                        'listBoxValues' => $this->languages()
                    ]
                ]
            ],
            'shortDescription' => 'Settings for Blog landing page',
            'topics' => [
                'blog',
            ],
            'steps' => [
                'step1' => [
                    'title' => 'Landing Page ',
                    'description' => 'Settings for Blog landing page',
                    'sections' => [
                        [
                            'title' => 'Entrypoint settings',
                            'description' => 'Leaving any of these fields empty will reset them to default value',
                            'form' => [
                                'entrypointMessage' => [
                                    'type' => 'text',
                                    'options' => [
                                        'name' => 'Entrypoint message'
                                    ]
                                ],
                                'backToStoreMessage' => [
                                    'type' => 'text',
                                    'options' => [
                                        'name' => 'Back to store message'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'title' => 'Landing page settings',
                            'description' => 'Empty fields will stay empty',
                            'form' => [
                                'urlName' => [
                                    'type' => 'text',
                                    'options' => [
                                        'name' => 'Custom URL',
                                        'minLength' => 3,
                                        'maxLength' => 20,
                                        'required' => true,
                                        'pattern' => '([a-z0-9-_])+'
                                    ]
                                ],
                                'landingTitle' => [
                                    'type' => 'text',
                                    'options' => [
                                        'name' => 'Landing page title'
                                    ]
                                ],
                                'landingMetaTitle' => [
                                    'type' => 'text',
                                    'options' => [
                                        'name' => 'Landing page meta title'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }


    /**
     * List of plugin sets for options
     *
     * @return array
     */
    private function listPluginSets()
    {
        $allSets = pluginApp(PluginSetRepositoryContract::class)->list();

        $selects = [];

        foreach($allSets as $set) {
            $selects[] = [
                'caption' => $set->name,
                'value' => $set->id
            ];
        }

        return $selects;
    }

    /**
     * @return array
     */
    private function languages()
    {
        $blogService = pluginApp(BlogService::class);
        $languages = $blogService->getLanguages();

        $selects = [];

        foreach($languages as $language) {
            $selects[] = [
                'caption' => "Languages.$language",
                'value' => $language
            ];
        }

        return $selects;

    }


}
