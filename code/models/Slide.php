<?php

class Slide extends Page
{
    /**
     * @var bool
     */
    private static $show_in_sitetree = false;

    /**
     * @var array
     */
    private static $allowed_children = array();

    /**
     * @var bool
     */
    private static $can_be_root = false;

    /**
     * @var array
     */
    private static $db = array(
        "Markup"    => "Text",
        "Styles"    => "Text",
        "Scripts"   => "Text",
    );

    /**
     * @var array
     */
    private static $belongs_to = array(
        "Deck" => "Deck",
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

        $markup = $this->getCodeEditorField("Markup", "Markup", "html");
        $styles = $this->getCodeEditorField("Styles", "Styles", "css");
        $scripts = $this->getCodeEditorField("Scripts", "Scripts", "js");

        $fields->addFieldsToTab("Root.Main", array(
            new TextField("Title", "Title"),
            $this->getToggleField("MarkupToggle", "Markup", $markup, true),
            $this->getToggleField("StylesToggle", "Styles", $styles),
            $this->getToggleField("ScriptsToggle", "Scripts", $scripts),
        ));

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
     * @param string $name
     * @param string $label
     * @param string $mode
     *
     * @return CodeEditorField
     */
    protected function getCodeEditorField($name, $label, $mode)
    {
        $field = new CodeEditorField($name, $label);
        $field->setMode($mode);
        $field->setTemplate("ReflowCodeEditorField");
        $field->setFieldHolderTemplate("ReflowCodeEditorFieldHolder");

        return $field;
    }

    /**
     * @param string    $name
     * @param string    $label
     * @param FormField $field
     * @param bool      $open
     *
     * @return ToggleCompositeField
     */
    protected function getToggleField($name, $label, $field, $open = false)
    {
        $field = new ToggleCompositeField($name, $label, array($field));
        $field->addExtraClass("code-editor-toggle");
        $field->setStartClosed(!$open);

        return $field;
    }
}
