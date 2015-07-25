<?php

class Slide extends Page
{
    /**
     * @var string
     */
    public static $default_sort = "SortOrder";
    /**
     * @var bool
     */
    private static $show_in_sitetree = false;
    /**
     * @var array
     */
    private static $allowed_children = [];
    /**
     * @var array
     */
    private static $db = [
        "Markup"    => "Text",
        "Styles"    => "Text",
        "Scripts"   => "Text",
        "SortOrder" => "Int",
    ];

    /**
     * @var array
     */
    private static $belongs_to = [
        "Deck" => "Deck",
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeFieldsFromTab("Root.Main", [
            "Title",
            "URLSegment",
            "MenuTitle",
            "Content",
            "Metadata",
        ]);

        Requirements::css("reflow/css/reflow.css");

        $markup = $this->getCodeEditorField("Markup", "Markup", "html");
        $styles = $this->getCodeEditorField("Styles", "Styles", "css");
        $scripts = $this->getCodeEditorField("Scripts", "Scripts", "js");

        $fields->addFieldsToTab("Root.Main", [
            new TextField("Title", "Title"),
            new TextField("URLSegment", "URL Segment"),
            $this->getToggleField("MarkupToggle", "Markup", $markup, true),
            $this->getToggleField("StylesToggle", "Styles", $styles),
            $this->getToggleField("ScriptsToggle", "Scripts", $scripts),
        ]);

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
        $field = new ToggleCompositeField($name, $label, [$field]);
        $field->addExtraClass("code-editor-toggle");
        $field->setStartClosed(!$open);

        return $field;
    }
}
