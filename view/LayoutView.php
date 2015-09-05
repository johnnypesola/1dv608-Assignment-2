<?php


namespace view;

class LayoutView {

    private $pageTitle = '';
    private $pageCharset = '';
    private $pageHeader = '';

    public function __construct($pageTitle, $pageHeader, $pageCharset = 'utf-8') {

        // Set values on object creation
        $this->pageTitle = $pageTitle;
        $this->pageHeader = $pageHeader;
        $this->pageCharset = $pageCharset;
    }


    public function render($isLoggedIn, LoginView $loginViewObj, DateTimeView $dateTimeViewObj) {

        // Render page header
        $this->renderHeader();

        // Render text about if the user is logged in or not
        $this->renderIsLoggedIn($isLoggedIn);

        echo '
              <div class="container">
                  ' . $loginViewObj->response() . '

                  ' . $dateTimeViewObj->show() . '
              </div>
        ';

        // Render page footer
        $this->renderFooter();
    }

    private function renderHeader() {
        echo '
            <!DOCTYPE html>
                  <html>
                    <head>
                      <meta charset="' . $this->pageCharset . '">
                      <title>' . $this->pageTitle . '</title>
                    </head>
                    <body>
                      <h1>' . $this->pageHeader . '</h1>
        ';
    }

    private function renderFooter() {
        echo '
                     </body>
                  </html>
        ';
    }
  
    private function renderIsLoggedIn($isLoggedIn) {
        echo ($isLoggedIn ? '<h2>Logged in</h2>' : '<h2>Not logged in</h2>');
    }
}
