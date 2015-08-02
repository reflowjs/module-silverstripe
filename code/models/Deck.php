<?php

/**
 * @method DataList Slides()
 */
class Deck extends Page
{
    /**
     * @var array
     */
    private static $db = array(
        "PreloadBefore" => "Int",
        "PreloadAfter"  => "Int",
        "UnloadBefore"  => "Int",
        "UnloadAfter"   => "Int",
        "Scale"         => "Decimal(1)",
        "Animation"     => "Varchar",
    );

    /**
     * @var array
     */
    private static $defaults = array(
        "PreloadBefore" => 3,
        "PreloadAfter"  => 3,
        "UnloadBefore"  => 3,
        "UnloadAfter"   => 3,
        "Scale"         => 1,
        "Animation"     => "none",
    );

    /**
     * @var array
     */
    private static $extensions = array(
        "Lumberjack",
    );

    /**
     * @var array
     */
    private static $allowed_children = array(
        "Slide",
    );

    /**
     * @var array
     */
    private static $has_many = array(
        "Slides" => "Slide",
    );

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        Requirements::css("reflow/public/css/reflow.css");
        Requirements::javascript("reflow/public/js/reflow.js");

        $fields->removeFieldsFromTab("Root.Main", array(
            "Title",
            "MenuTitle",
            "Content",
            "Metadata",
        ));

        $fields->addFieldsToTab("Root.Main", array(
            new TextField("Title", "Title"),
            new DropdownField("Animation", "Animation", array("none" => "None", "fade" => "Fade")),
            $this->getNumericField("Scale", "Scale")->setAttribute("step", ".1")->setAttribute("max", 1),
            $this->getNumericField("PreloadBefore", "Preload Pages Before"),
            $this->getNumericField("PreloadAfter", "Preload Pages After"),
            $this->getNumericField("UnloadBefore", "Unload Pages Before"),
            $this->getNumericField("UnloadAfter", "Unload Pages After"),
        ));

        /** @var Tab $tab */
        $tab = $fields->findOrMakeTab("Root.Main");
        $tab->setTitle("Deck");

        return $fields;
    }

    public function getSettingsFields()
    {
        $fields = parent::getSettingsFields();

        $fields->removeFieldsFromTab("Root.Settings", array(
            "ClassName",
            "ParentType",
            "ParentID",
            "Visibility",
        ));

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

        $config->addComponent(new GridFieldVersionedOrderableRows());

        /** @var GridFieldPaginator $paginator */
        $paginator = $config->getComponentByType("GridFieldPaginator");
        $paginator->setItemsPerPage(999);

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

    /**
     * @param string $name
     * @param string $title
     *
     * @return NumericField
     */
    protected function getNumericField($name, $title)
    {
        $field = new NumericField($name, $title);
        $field->setAttribute("type", "number");
        $field->setAttribute("min", 0);

        return $field;
    }
}
