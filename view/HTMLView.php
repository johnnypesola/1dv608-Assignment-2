<?php


namespace view;

class HTMLView {

    private $pageTitle = '';
    private $pageCharset = '';
    private $pageHeader = '';

    public function __construct($pageTitle, $pageHeader, $pageCharset = 'utf-8') {

        // Set values on object creation
        $this->pageTitle = $pageTitle;
        $this->pageHeader = $pageHeader;
        $this->pageCharset = $pageCharset;

    }

// Public methods
    public function Render($output) {

        // Render page header
        $this->RenderHeader();

        // Render page output
        echo $output;

        // Render page footer
        $this->RenderFooter();
    }

    // Private methods
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


                <div class="container" >
        ';

        //' . $this->GetLoggedIn() . '
    }

    private function RenderFooter() {
        /*echo '<p>' . $this->GetTime() . '</p>
                </div>
            </body>
        </html>
        ';
        */
    }
}
