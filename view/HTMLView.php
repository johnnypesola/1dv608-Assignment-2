<?php


namespace view;

class HTMLView {

// Init variables
    private $pageTitle = '';
    private $pageCharset = '';
    private $pageHeader = '';

// Constructor
    public function __construct($pageTitle, $pageHeader, $pageCharset = 'utf-8') {

        // Set values on object creation
        $this->pageTitle = $pageTitle;
        $this->pageHeader = $pageHeader;
        $this->pageCharset = $pageCharset;

    }

// Public Methods
    public function Render($output) {

        // Render page header
        $this->RenderHeader();

        // Render page output
        echo $output;

        // Render page footer
        $this->RenderFooter();
    }

// Private Methods
    private function RenderHeader() {
        echo '
        <!DOCTYPE html>
            <html>
                <head>
                <meta charset="' . $this->pageCharset . '">
                <title>' . $this->pageTitle . '</title>
                <link rel="stylesheet" href="css/style.css">
            </head>
            <body>
                <h1>' . $this->pageHeader . '</h1>


                <div class="container" >
        ';
    }

    private function RenderFooter() {
        echo $this->GetTimeOutput() . '
                </div>
            </body>
        </html>
        ';

    }

    private function GetTimeOutput() {
        return '<p>' . date('l, \t\h\e jS \o\f F Y, \T\h\e \t\i\m\e \i\s H:i:s') . '</p>';
    }
}
