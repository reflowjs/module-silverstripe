<?php

class ReflowBuildTask extends BuildTask
{
    public function run($request)
    {
        $path = REFLOW_DIR . "/public/thirdparty";

        exec("cd {$path} && rm -rf reflow-silverstripe && git clone git@github.com:assertchris/reflow-silverstripe.git");
        exec("cd {$path} && rm -rf reflow-core && git clone git@github.com:assertchris/reflow-core.git && cd reflow-core && npm install && npm run build");
    }
}