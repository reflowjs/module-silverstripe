<?php

/**
 * @method DataList Slides()
 */
class Deck extends Page
{
    /**
     * @var array
     */
    private static $extensions = [
        "Lumberjack",
    ];

    /**
     * @var array
     */
    private static $allowed_children = array(
        "Slide",
    );

    /**
     * @var array
     */
    private static $has_many = [
        "Slides" => "Slide",
    ];

    protected $foo;

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeFieldFromTab("Root", "Main");

        return $fields;
    }

    public function getSettingsFields()
    {
        $fields = parent::getSettingsFields();

        $fields->removeFieldsFromTab("Root.Settings", [
            "ClassName",
            "ParentType",
            "ParentID",
            "Visibility",
        ]);

        $fields->addFieldsToTab("Root.Settings", [
            new TextField("Title", "Title"),
            new TextField("URLSegment", "URL Segment"),
        ]);

        return $fields;
    }

    /**
     * @return string
     */
    public function getLumberjackTitle()
    {
        return "Slides";
    }

    /**
     * @return GridFieldConfig
     */
    public function getLumberjackGridFieldConfig()
    {
        $config = GridFieldConfig_Lumberjack::create();

        $config->addComponent(new GridFieldSortableRows("SortOrder"));

        return $config;
    }

    /**
     * @return DataList
     */
    public function getLumberjackChildPages()
    {
        return Slide::get()->filter(array(
            "ParentID" => $this->ID,
        ));
    }
}
