<?php

use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Rule\InMemoryRule;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use fpdf\FPDF;
use JonnyW\PhantomJs\Client;

/**
 * @mixin Deck
 */
class Deck_Controller extends ContentController
{
    /**
     * @var array
     */
    private static $allowed_actions = array(
        "Export",
    );

    /**
     * @return string
     */
    public function Export()
    {
        $assetPath = ASSETS_PATH;
        $reflowPath = REFLOW_DIR;
        $deckSegment = $this->URLSegment;

        $manager = new ProcessManager();
        $manager->setLogPath(ASSETS_PATH);

        $rule = new InMemoryRule();
        $rule->setProcesses(5);
        $rule->setMinimumProcessorUsage(0);
        $rule->setMaximumProcessorUsage(100);

        $manager->addRule($rule);

        $manager->addTask(new ProcessCallbackTask(function() use ($assetPath, $deckSegment) {
            exec("mkdir {$assetPath}/{$deckSegment}");
        }));

        /** @var Slide $slide */
        foreach ($this->Slides() as $slide) {
            $slideLink = $slide->AbsoluteLink("Capture");
            $slideSegment = $slide->URLSegment;

            $link = rtrim($this->AbsoluteLink(), "/") . "#" . $slide->URLSegment;

            $manager->addTask(new ProcessCallbackTask(function() use ($link, $assetPath, $reflowPath, $slideLink, $slideSegment, $deckSegment) {
                $client = Client::getInstance();
                $client->setBinDir($reflowPath . "/../vendor/bin");

                /**
                 * @see JonnyW\PhantomJs\Message\CaptureRequest
                 **/
                $request = $client->getMessageFactory()->createCaptureRequest($link, "GET");

                $request->setViewportSize(1440, 900); // TODO
                $request->setDelay(3); // TODO
                $request->setCaptureFile("{$assetPath}/{$deckSegment}/{$slideSegment}.png");

                /**
                 * @see JonnyW\PhantomJs\Message\Response
                 **/
                $response = $client->getMessageFactory()->createResponse();

                // Send the request
                $client->send($request, $response);
            }));

            print "Exporting: " . $slide->Title . "<br>";
        }

        $slides = [];

        /** @var Slide $slide */
        foreach ($this->Slides() as $slide) {
            $slideSegment = $slide->URLSegment;

            $slides[] = "{$assetPath}/{$deckSegment}/{$slideSegment}.png";
        }

        while ($manager->tick()) {
            usleep(10);
        }

        $manager->addTask(new ProcessCallbackTask(function() use ($assetPath, $deckSegment, $slides) {
            $pdf = new FPDF();

            /** @var Slide $slide */
            foreach ($slides as $slide) {
                $pdf->AddPage("L", array(15 * 72, 9.38 * 72));
                $pdf->Image($slide, 0, 0, 15 * 72, 9.38 * 72);
            }

            $pdf->Output("{$assetPath}/{$deckSegment}/export.pdf", "F");
        }));

        while ($manager->tick()) {
            usleep(10);
        }
    }
}
