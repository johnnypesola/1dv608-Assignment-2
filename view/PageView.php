<?php


namespace view;

class PageView {

    private $pageTitle = '';
    private $pageCharset = '';
    private $pageHeader = '';

    public function __construct($pageTitle, $pageHeader, $pageCharset = 'utf-8') {

        // Set values on object creation
        $this->pageTitle = $pageTitle;
        $this->pageHeader = $pageHeader;
        $this->pageCharset = $pageCharset;

    }


    public function Render($contentViewObj) {

        // Render page header
        $this->RenderHeader();

        // Render content view
        $contentViewObj->Render();

        // Render page footer
        $this->RenderFooter();
    }

    private function RenderHeader() {
        echo '
        <!DOCTYPE html>
            <html>
                <head>
                <meta charset="' . $this->pageCharset . '">
                <title>' . $this->pageTitle . '</title>
            </head>
            <body>
                <h1>' . $this->pageHeader . '</h1>
                ' . (\model\UsersModelDAL::IsUserLoggedIn() ? '<h2>Logged in</h2>' : '<h2>Not logged in</h2>') . '

                <div class="container" >
        ';
    }

    private function RenderFooter() {
        echo $this->RenderTime() . '
                </div>
            </body>
        </html>
        ';
    }

    public function RenderTime() {

        $timeString = date('l, \t\h\e jS \o\f F Y, \T\h\e \t\i\m\e \i\s H:i:s');

        return '<p>' . $timeString . '</p>';
    }
}
