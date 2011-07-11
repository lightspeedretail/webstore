<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

    // TODO : What is the origin of this file ?

    class XLSMimeMail
{
    /**
    * The html part of the message
    * @var string
    */
    private $html;

    /**
    * The text part of the message(only used in TEXT only messages)
    * @private string
    */
    private $text;

    /**
    * The main body of the message after building
    * @private string
    */
    private $output;

    /**
    * An array of embedded images   /objects
    * @private array
    */
    private $html_images;

    /**
    * An array of recognised image types for the findHtmlImages() method
    * @private array
    */
    private $image_types;

    /**
    * Parameters that affect the build process
    * @private array
    */
    private $build_params;

    /**
    * Array of attachments
    * @private array
    */
    private $attachments;

    /**
    * The main message headers
    * @private array
    */
    private $headers;

    /**
    * Whether the message has been built or not
    * @private boolean
    */
    private $is_built;
    
    /**
    * The return path address. If not set the From:
    * address is used instead
    * @private string
    */
    private $return_path;
    
    /**
    * Array of information needed for smtp sending
    * @private array
    */
    private $smtp_params;

    /**
    * Sendmail path. Do not include -f
    * @private $sendmail_path
    */
    private $sendmail_path;

    /**
    * Constructor function.
    */
    public function __construct()
    {
        /**
        * Initialise some variables.
        */
        $this->attachments   = array();
        $this->html_images   = array();
        $this->headers       = array();
        $this->is_built      = false;
        $this->text          = '';
        $this->sendmail_path = '/usr/lib/sendmail -ti';

        /**
        * If you want the auto load functionality
        * to find other image/file types, add the
        * extension and content type here.
        */
        $this->image_types = array('gif'  => 'image/gif',
                                   'jpg'  => 'image/jpeg',
                                   'jpeg' => 'image/jpeg',
                                   'jpe'  => 'image/jpeg',
                                   'bmp'  => 'image/bmp',
                                   'png'  => 'image/png',
                                   'tif'  => 'image/tiff',
                                   'tiff' => 'image/tiff',
                                   'swf'  => 'application/x-shockwave-flash');

        /**
        * Set these up
        */
        $this->build_params['html_encoding'] = new QPrintEncoding();
        $this->build_params['text_encoding'] = new SevenBitEncoding();
        $this->build_params['html_charset']  = 'ISO-8859-1';
        $this->build_params['text_charset']  = 'ISO-8859-1';
        $this->build_params['head_charset']  = 'ISO-8859-1';
        $this->build_params['text_wrap']     = 998;

        /**
        * Defaults for smtp sending
        */
        if (!empty($_SERVER['HTTP_HOST'])) {
            $helo = $_SERVER['HTTP_HOST'];

        } elseif (!empty($_SERVER['SERVER_NAME'])) {
            $helo = $_SERVER['SERVER_NAME'];
        
        } else {
            $helo = 'localhost';
        }

        $this->smtp_params['host'] = 'localhost';
        $this->smtp_params['port'] = 25;
        $this->smtp_params['helo'] = $helo;
        $this->smtp_params['auth'] = false;
        $this->smtp_params['user'] = '';
        $this->smtp_params['pass'] = '';

        /**
        * Make sure the MIME version header is first.
        */
        $this->headers['MIME-Version'] = '1.0';
        $this->headers['X-Mailer'] = 'LightSpeed WebStore ' . _xls_version();
    }

    /**
    * Accessor to set the CRLF style
    * 
    * @param string $crlf CRLF style to use.
    *                     Use \r\n for SMTP, and \n
    *                     for normal.
    */
    public function setCRLF($crlf = "\n")
    {
        if (!defined('CRLF')) {
            define('CRLF', $crlf, true);
        }

        if (!defined('MAIL_MIMEPART_CRLF')) {
            define('MAIL_MIMEPART_CRLF', $crlf, true);
        }
    }

    /**
    * Accessor to set the SMTP parameters
    * 
    * @param string $host Hostname
    * @param string $port Port
    * @param string $helo HELO string to use
    * @param bool   $auth User authentication or not
    * @param string $user Username
    * @param string $pass Password
    */
    public function setSMTPParams($host = null, $port = null, $helo = null, $auth = null, $user = null, $pass = null)
    {
        if (!is_null($host)) $this->smtp_params['host'] = $host;
        if (!is_null($port)) $this->smtp_params['port'] = $port;
        if (!is_null($helo)) $this->smtp_params['helo'] = $helo;
        if (!is_null($auth)) $this->smtp_params['auth'] = $auth;
        if (!is_null($user)) $this->smtp_params['user'] = $user;
        if (!is_null($pass)) $this->smtp_params['pass'] = $pass;
    }

    /**
    * Sets sendmail path and options (optionally) (when directly piping to sendmail)
    * 
    * @param string $path Path and options for sendmail command
    */
    public function setSendmailPath($path)
    {
        $this->sendmail_path = $path;
    }

    /**
    * Accessor function to set the text encoding
    * 
    * @param object $encoding Text encoding to use
    */
    public function setTextEncoding(iEncoding $encoding)
    {
        $this->build_params['text_encoding'] = $encoding;
    }

    /**
    * Accessor function to set the HTML encoding
    * 
    * @param object $encoding HTML encoding to use
    */
    public function setHTMLEncoding(iEncoding $encoding)
    {
        $this->build_params['html_encoding'] = $encoding;
    }
    
    /**
    * Accessor function to set the text charset
    * 
    * @param string $charset Character set to use
    */
    public function setTextCharset($charset = 'ISO-8859-1')
    {
        $this->build_params['text_charset'] = $charset;
    }

    /**
    * Accessor function to set the HTML charset
    * 
    * @param string $charset Character set to use
    */
    public function setHTMLCharset($charset = 'ISO-8859-1')
    {
        $this->build_params['html_charset'] = $charset;
    }

    /**
    * Accessor function to set the header encoding charset
    * 
    * @param string $charset Character set to use
    */
    public function setHeadCharset($charset = 'ISO-8859-1')
    {
        $this->build_params['head_charset'] = $charset;
    }

    /**
    * Accessor function to set the text wrap count
    * 
    * @param integer $count Point at which to wrap text
    */
    public function setTextWrap($count = 998)
    {
        $this->build_params['text_wrap'] = $count;
    }

    /**
    * Accessor to set a header
    * 
    * @param string $name  Name of header
    * @param string $value Value of header
    */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
    * Accessor to add a Subject: header
    * 
    * @param string $subject Subject to set
    */
    public function setSubject($subject)
    {
        $this->headers['Subject'] = $subject;
    }

    /**
    * Accessor to add a From: header
    * 
    * @param string $from From address
    */
    public function setFrom($from)
    {
        $this->headers['From'] = $from;
    }
    
    /**
    * Accessor to set priority. Priority given should be either
    * high, normal or low. Can also be specified numerically, 
    * being 1, 3 or 5 (respectively).
    * 
    * @param mixed $priority The priority to use.
    */
    public function setPriority($priority = 'normal')
    {
        switch (strtolower($priority)) {
            case 'high':
            case '1':
                $this->headers['X-Priority'] = '1';
                $this->headers['X-MSMail-Priority'] = 'High';
                break;

            case 'normal':
            case '3':
                $this->headers['X-Priority'] = '3';
                $this->headers['X-MSMail-Priority'] = 'Normal';
                break;

            case 'low':
            case '5':
                $this->headers['X-Priority'] = '5';
                $this->headers['X-MSMail-Priority'] = 'Low';
                break;
        }
    }

    /**
    * Accessor to set the return path
    * 
    * @param string $return_path Return path to use
    */
    public function setReturnPath($return_path)
    {
        $this->return_path = $return_path;
    }

    /**
    * Accessor to add a Cc: header
    * 
    * @param string $cc Carbon Copy address
    */
    public function setCc($cc)
    {
        $this->headers['Cc'] = $cc;
    }

    /**
    * Accessor to add a Bcc: header
    * 
    * @param string $bcc Blind Carbon Copy address
    */
    public function setBcc($bcc)
    {
        $this->headers['Bcc'] = $bcc;
    }

    /**
    * Adds plain text. Use this function
    * when NOT sending html email
    * 
    * @param string $text Plain text of email
    */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
    * Adds HTML to the emails, with an associated text part.
    * If third part is given, images in the email will be loaded
    * from this directory.
    * 
    * @param string $html       HTML part of email
    * @param string $images_dir Images directory
    */
    function setHTML($html, $images_dir = null)
    {
        $this->html = $html;

        if (!empty($images_dir)) {
            $this->findHtmlImages($images_dir);
        }
    }

    /**
    * Function for extracting images from
    * html source. This function will look
    * through the html code supplied by setHTML()
    * and find any file that ends in one of the
    * extensions defined in $obj->image_types.
    * If the file exists it will read it in and
    * embed it, (not an attachment).
    * 
    * @param string $images_dir Images directory to look in
    */
    private function findHtmlImages($images_dir)
    {
        // Build the list of image extensions
        $extensions = array_keys($this->image_types);

        preg_match_all('/(?:"|\')([^"\']+\.('.implode('|', $extensions).'))(?:"|\')/Ui', $this->html, $matches);

        foreach ($matches[1] as $m) {
            if (file_exists($images_dir . $m)) {
                $html_images[] = $m;
                $this->html = str_replace($m, basename($m), $this->html);
            }
        }

        /**
        * Go thru found images
        */
        if (!empty($html_images)) {

            // If duplicate images are embedded, they may show up as attachments, so remove them.
            $html_images = array_unique($html_images);
            sort($html_images);

            foreach ($html_images as $img) {
                if ($image = file_get_contents($images_dir . $img)) {
                    $ext          = preg_replace('#^.*\.(\w{3,4})$#e', 'strtolower("$1")', $img);
                    $content_type = $this->image_types[$ext];
                    $this->addEmbeddedImage(new stringEmbeddedImage($image, basename($img), $content_type));
                }
            }
        }
    }

    /**
    * Adds an image to the list of embedded
    * images.
    * 
    * @param string $object Embedded image object
    */
    public function addEmbeddedImage($embeddedImage)
    {
        $embeddedImage->cid = md5(uniqid(time()));

        $this->html_images[] = $embeddedImage;
    }


    /**
    * Adds a file to the list of attachments.
    * 
    * @param string $attachment Attachment object
    */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;
    }

    /**
    * Adds a text subpart to a mime_part object
    * 
    * @param  object $obj 
    * @return object      Mime part object
    */
    private function addTextPart(&$message)
    {
        $params['content_type'] = 'text/plain';
        $params['encoding']     = $this->build_params['text_encoding']->getType();
        $params['charset']      = $this->build_params['text_charset'];

        if (!empty($message)) {
            $message->addSubpart($this->text, $params);
        } else {
            $message = new XLS_MIMEPart($this->text, $params);
        }
    }

    /**
    * Adds a html subpart to a mime_part object
    * 
    * @param object $obj
    * @return object     Mime part object
    */
    private function addHtmlPart(&$message)
    {
        $params['content_type'] = 'text/html';
        $params['encoding']     = $this->build_params['html_encoding']->getType();
        $params['charset']      = $this->build_params['html_charset'];

        if (!empty($message)) {
            $message->addSubpart($this->html, $params);
        } else {
            $message = new XLS_MIMEPart($this->html, $params);
        }
    }

    /**
    * Starts a message with a mixed part
    * 
    * @return object Mime part object
    */
    private function addMixedPart(&$message)
    {
        $params['content_type'] = 'multipart/mixed';
        
        $message = new XLS_MIMEPart('', $params);
    }

    /**
    * Adds an alternative part to a mime_part object
    * 
    * @param  object $obj
    * @return object      Mime part object
    */
    private function addAlternativePart(&$message)
    {
        $params['content_type'] = 'multipart/alternative';

        if (!empty($message)) {
            return $message->addSubpart('', $params);
        } else {
            $message = new XLS_MIMEPart('', $params);
        }
    }

    /**
    * Adds a html subpart to a mime_part object
    * 
    * @param  object $obj
    * @return object      Mime part object
    */
    private function addRelatedPart(&$message)
    {
        $params['content_type'] = 'multipart/related';

        if (!empty($message)) {
            return $message->addSubpart('', $params);
        } else {
            $message = new XLS_MIMEPart('', $params);
        }
    }

    /**
    * Adds all html images to a mime_part object
    * 
    * @param  object $obj Message object
    */
    private function addHtmlImageParts(&$message)
    {
        foreach ($this->html_images as $value) {
            $params['content_type'] = $value->contentType;
            $params['encoding']     = $value->encoding->getType();
            $params['disposition']  = 'inline';
            $params['dfilename']    = $value->name;
            $params['cid']          = $value->cid;
    
            $message->addSubpart($value->data, $params);
        }
    }

    /**
    * Adds all attachments to a mime_part object
    * 
    * @param object $obj Message object
    */
    private function addAttachmentParts(&$message)
    {
        foreach ($this->attachments as $value) {
            $params['content_type'] = $value->contentType;
            $params['encoding']     = $value->encoding->getType();
            $params['disposition']  = 'attachment';
            $params['dfilename']    = $value->name;
        
            $message->addSubpart($value->data, $params);
        }
    }

    /**
    * Builds the multipart message.
    */
    private function build()
    {
        if (!empty($this->html_images)) {
            foreach ($this->html_images as $value) {
                $quoted = preg_quote($value->name);
                $cid    = preg_quote($value->cid);

                $this->html = preg_replace("#src=\"$quoted\"|src='$quoted'#", "src=\"cid:$cid\"", $this->html);
                $this->html = preg_replace("#background=\"$quoted\"|background='$quoted'#", "background=\"cid:$cid\"", $this->html);
            }
        }

        $message     = null;
        $attachments = !empty($this->attachments);
        $html_images = !empty($this->html_images);
        $html        = !empty($this->html);
        $text        = !$html;

        switch (true) {
            case $text:
                $message = null;
                if ($attachments) {
                    $this->addMixedPart($message);
                }

                $this->addTextPart($message);

                // Attachments
                $this->addAttachmentParts($message);
                break;

            case $html AND !$attachments AND !$html_images:
                $this->addAlternativePart($message);

                $this->addTextPart($message);
                $this->addHtmlPart($message);
                break;

            case $html AND !$attachments AND $html_images:
                $this->addRelatedPart($message);
                $alt = $this->addAlternativePart($message);

                $this->addTextPart($alt);
                $this->addHtmlPart($alt);
                
                // HTML images
                $this->addHtmlImageParts($message);
                break;

            case $html AND $attachments AND !$html_images:
                $this->addMixedPart($message);
                $alt = $this->addAlternativePart($message);

                $this->addTextPart($alt);
                $this->addHtmlPart($alt);
                
                // Attachments
                $this->addAttachmentParts($message);
                break;

            case $html AND $attachments AND $html_images:
                $this->addMixedPart($message);
                $rel = $this->addRelatedPart($message);
                $alt = $this->addAlternativePart($rel);
                
                $this->addTextPart($alt);
                $this->addHtmlPart($alt);
                
                // HTML images
                $this->addHtmlImageParts($rel);
                
                // Attachments
                $this->addAttachmentParts($message);
                break;

        }

        if (isset($message)) {
            $output = $message->encode();
            $this->output   = $output['body'];
            $this->headers  = array_merge($this->headers, $output['headers']);

            // Figure out hostname
            if (!empty($_SERVER['HTTP_HOST'])) {
                $hostname = $_SERVER['HTTP_HOST'];
            
            } else if (!empty($_SERVER['SERVER_NAME'])) {
                $hostname = $_SERVER['SERVER_NAME'];
            
            } else if (!empty($_ENV['HOSTNAME'])) {
                $hostname = $_ENV['HOSTNAME'];
            
            } else {
                $hostname = 'localhost';
            }
            
            $message_id = sprintf('<%s.%s@%s>', base_convert(time(), 10, 36), base_convert(rand(), 10, 36), $hostname);
            $this->headers['Message-ID'] = $message_id;

            $this->is_built = true;
            return true;
        } else {
            return false;
        }
    }

    /**
    * Function to encode a header if necessary
    * according to RFC2047
    * 
    * @param  string $input   Value to encode
    * @param  string $charset Character set to use
    * @return string          Encoded value
    */
    private function encodeHeader($input, $charset = 'ISO-8859-1')
    {
        preg_match_all('/(\w*[\x80-\xFF]+\w*)/', $input, $matches);
        foreach ($matches[1] as $value) {
            $replacement = preg_replace('/([\x80-\xFF])/e', '"=" . strtoupper(dechex(ord("\1")))', $value);
            $input = str_replace($value, '=?' . $charset . '?Q?' . $replacement . '?=', $input);
        }
        
        return $input;
    }

    /**
    * Sends the mail.
    *
    * @param  array  $recipients Array of receipients to send the mail to
    * @param  string $type       How to send the mail ('mail' or 'sendmail' or 'smtp')
    * @return mixed
    */
    public function send($recipients, $type = 'mail')
    {
        if (!defined('CRLF')) {
            $this->setCRLF( ($type == 'mail' OR $type == 'sendmail') ? "\n" : "\r\n");
        }

        if (!$this->is_built) {
            $this->build();
        }

        switch ($type) {
            case 'mail':
                $subject = '';
                if (!empty($this->headers['Subject'])) {
                    $subject = $this->encodeHeader($this->headers['Subject'], $this->build_params['head_charset']);
                    unset($this->headers['Subject']);
                }

                // Get flat representation of headers
                foreach ($this->headers as $name => $value) {
                    $headers[] = $name . ': ' . $this->encodeHeader($value, $this->build_params['head_charset']);
                }

                $to = $this->encodeHeader(implode(', ', $recipients), $this->build_params['head_charset']);

                if (!empty($this->return_path)) {
                    $result = mail($to, $subject, $this->output, implode(CRLF, $headers), '-f' . $this->return_path);
                } else {
                    $result = mail($to, $subject, $this->output, implode(CRLF, $headers));
                }

                // Reset the subject in case mail is resent
                if ($subject !== '') {
                    $this->headers['Subject'] = $subject;
                }


                // Return
                return $result;
                break;

            case 'sendmail':
                // Get flat representation of headers
                foreach ($this->headers as $name => $value) {
                    $headers[] = $name . ': ' . $this->encodeHeader($value, $this->build_params['head_charset']);
                }

                // Encode To:
                $headers[] = 'To: ' . $this->encodeHeader(implode(', ', $recipients), $this->build_params['head_charset']);

                // Get return path arg for sendmail command if necessary
                $returnPath = '';
                if (!empty($this->return_path)) {
                    $returnPath = '-f' . $this->return_path;
                }

                $pipe = popen($this->sendmail_path . " " . $returnPath, 'w');
                    $bytes = fputs($pipe, implode(CRLF, $headers) . CRLF . CRLF . $this->output);
                $r = pclose($pipe);

                return $r;
                break;

            case 'smtp':
                //require_once(dirname(__FILE__) . '/smtp.php');
                //require_once(dirname(__FILE__) . '/RFC822.php');
                $smtp = new XLSSMTP($this->smtp_params); // ASHIK
                
                $smtp->connect();
                
               $XLS_RFC822 = new XLS_RFC822();
                // Parse recipients argument for internet addresses
                foreach ($recipients as $recipient) {
                    
                    $addresses = $XLS_RFC822->parseAddressList($recipient, $this->smtp_params['helo'], null, false);
                    foreach ($addresses as $address) {
                        $smtp_recipients[] = sprintf('%s@%s', $address->mailbox, $address->host);
                    }
                }
                unset($addresses); // These are reused
                unset($address);   // These are reused

                // Get flat representation of headers, parsing
                // Cc and Bcc as we go
                foreach ($this->headers as $name => $value) {
                    if ($name == 'Cc' OR $name == 'Bcc') {
                        $addresses = $XLS_RFC822->parseAddressList($value, $this->smtp_params['helo'], null, false);
                        foreach ($addresses as $address) {
                            $smtp_recipients[] = sprintf('%s@%s', $address->mailbox, $address->host);
                        }
                    }
                    if ($name == 'Bcc') {
                        continue;
                    }
                    $headers[] = $name . ': ' . $this->encodeHeader($value, $this->build_params['head_charset']);
                }
                // Add To header based on $recipients argument
                $headers[] = 'To: ' . $this->encodeHeader(implode(', ', $recipients), $this->build_params['head_charset']);

                // Add headers to send_params
                $send_params['headers']    = $headers;
                $send_params['recipients'] = array_values(array_unique($smtp_recipients));
                $send_params['body']       = $this->output;

                // Setup return path
                if (isset($this->return_path)) {
                    $send_params['from'] = $this->return_path;
                } elseif (!empty($this->headers['From'])) {
                    $from = $XLS_RFC822->parseAddressList($this->headers['From']);
                    $send_params['from'] = sprintf('%s@%s', $from[0]->mailbox, $from[0]->host);
                } else {
                    $send_params['from'] = 'postmaster@' . $this->smtp_params['helo'];
                }

                // Send it
                if (!$smtp->send($send_params)) {
                    $this->errors = $smtp->getErrors();
                    error_log(print_r($this->errors , true));
                    _xls_log(print_r($this->errors,TRUE));
                    return false;
                }
                return true;
                break;
        }
    }

    /**
    * Use this method to return the email
    * in message/rfc822 format. Useful for
    * adding an email to another email as
    * an attachment. there's a commented
    * out example in example.php.
    * 
    * @param array  $recipients Array of recipients 
    * @param string $type       Method to be used to send the mail.
    *                           Used to determine the line ending type.
    */
    public function getRFC822($recipients, $type = 'mail')
    {
        // Make up the date header as according to RFC822
        $this->setHeader('Date', date('D, d M y H:i:s O'));

        if (!defined('CRLF')) {
            $this->setCRLF($type == 'mail' ? "\n" : "\r\n");
        }

        if (!$this->is_built) {
            $this->build();
        }

        // Return path ?
        if (isset($this->return_path)) {
            $headers[] = 'Return-Path: ' . $this->return_path;
        }

        // Get flat representation of headers
        foreach ($this->headers as $name => $value) {
            $headers[] = $name . ': ' . $value;
        }
        $headers[] = 'To: ' . implode(', ', $recipients);

        return implode(CRLF, $headers) . CRLF . CRLF . $this->output;
    }
} // End of class.


/**
* Attachment classes
*/
class attachment
{
    /**
    * Data of attachment
    * @var string
    */
    public $data;
    
    /**
    * Name of attachment (filename)
    * @var string
    */
    public $name;
    
    /**
    * Content type of attachment
    * @var string
    */
    public $contentType;
    
    /**
    * Encoding type of attachment
    * @var object
    */
    public $encoding;
    
    /**
    * Constructor
    * 
    * @param string $data        File data
    * @param string $name        Name of attachment (filename)
    * @param string $contentType Content type of attachment
    * @param object $encoding    Encoding type to use
    */
    public function __construct($data, $name, $contentType, iEncoding $encoding)
    {
        $this->data        = $data;
        $this->name        = $name;
        $this->contentType = $contentType;
        $this->encoding    = $encoding;
    }
}


/**
* File based attachment class
*/
class fileAttachment extends attachment
{
    /**
    * Constructor
    * 
    * @param string $filename    Name of file
    * @param string $contentType Content type of file
    * @param string $encoding    What encoding to use
    */
    public function __construct($filename, $contentType = 'application/octet-stream', $encoding = null)
    {
        $encoding = is_null($encoding) ? new Base64Encoding() : $encoding;

        parent::__construct(file_get_contents($filename), basename($filename), $contentType, $encoding);
    }
}


/**
* Attachment class to handle attachments which are contained
* in a variable.
*/
class stringAttachment extends attachment
{
    /**
    * Constructor
    * 
    * @param string $data        File data
    * @param string $name        Name of attachment (filename)
    * @param string $contentType Content type of file
    * @param string $encoding    What encoding to use
    */
    public function __construct($data, $name = '', $contentType = 'application/octet-stream', $encoding = null)
    {
        $encoding = is_null($encoding) ? new Base64Encoding() : $encoding;
        
        parent::__construct($data, $name, $contentType, $encoding);
    }
}


/**
* File based embedded image class
*/
class fileEmbeddedImage extends fileAttachment
{
}


/**
* String based embedded image class
*/
class stringEmbeddedImage extends stringAttachment
{
}


/**
* 
*/
/**
* Encoding interface
*/
interface iEncoding
{
    public function encode($input);
    public function getType();
}


/**
* Base64 Encoding class
*/
class Base64Encoding implements iEncoding
{
    /*
    * Function to encode data using
    * base64 encoding.
    * 
    * @param string $input Data to encode
    */
    public function encode($input)
    {
        return rtrim(chunk_split(base64_encode($input), 76, defined('MAIL_MIME_PART_CRLF') ? MAIL_MIME_PART_CRLF : "\r\n"));
    }
    
    /**
    * Returns type
    */
    public function getType()
    {
        return 'base64';
    }
}


/**
* Quoted Printable Encoding class
*/
class QPrintEncoding implements iEncoding
{
    /*
    * Function to encode data using
    * quoted-printable encoding.
    * 
    * @param string $input Data to encode
    */
    public function encode($input)
    {
        // Replace non printables
        $input    = preg_replace('/([^\x20\x21-\x3C\x3E-\x7E\x0A\x0D])/e', 'sprintf("=%02X", ord("\1"))', $input);
        $inputLen = strlen($input);
        $outLines = array();
        $output   = '';

        $lines = preg_split('/\r?\n/', $input);
        
        // Walk through each line
        for ($i=0; $i<count($lines); $i++) {
            // Is line too long ?
            if (strlen($lines[$i]) > $lineMax) {
                $outLines[] = substr($lines[$i], 0, $lineMax - 1) . "="; // \r\n Gets added when lines are imploded
                $lines[$i] = substr($lines[$i], $lineMax - 1);
                $i--; // Ensure this line gets redone as we just changed it
            } else {
                $outLines[] = $lines[$i];
            }
        }
        
        // Convert trailing whitespace      
        $output = preg_replace('/(\x20+)$/me', 'str_replace(" ", "=20", "\1")', $outLines);

        return implode("\r\n", $output);
    }
    
    /**
    * Returns type
    */
    public function getType()
    {
        return 'quoted-printable';
    }
}


/**
* 7Bit Encoding class
*/
class SevenBitEncoding implements iEncoding
{
    /*
    * Function to "encode" data using
    * 7bit encoding.
    * 
    * @param string $input Data to encode
    */
    public function encode($input)
    {
        return $input;
    }
    
    /**
    * Returns type
    */
    public function getType()
    {
        return '7bit';
    }
}


/**
* 8Bit Encoding class
*/
class EightBitEncoding implements iEncoding
{
    /*
    * Function to "encode" data using
    * 8bit encoding.
    * 
    * @param string $input Data to encode
    */
    public function encode($input)
    {
        return $input;
    }
    
    /**
    * Returns type
    */
    public function getType()
    {
        return '8bit';
    }
}




class XLS_MIMEPart
{
    /**
    * The encoding type of this part
    * @var string
    */
    private $encoding;

    /**
    * An array of subparts
    * @var array
    */
    private $subparts;

    /**
    * The output of this part after being built
    * @var string
    */
    private $encoded;

    /**
    * Headers for this part
    * @var array
    */
    private $headers;

    /**
    * The body of this part (not encoded)
    * @var string
    */
    private $body;

    /**
    * Constructor.
    *
    * Sets up the object.
    *
    * @param $body   - The body of the mime part if any.
    * @param $params - An associative array of parameters:
    *                  content_type - The content type for this part eg multipart/mixed
    *                  encoding     - The encoding to use, 7bit, 8bit, base64, or quoted-printable
    *                  cid          - Content ID to apply
    *                  disposition  - Content disposition, inline or attachment
    *                  dfilename    - Optional filename parameter for content disposition
    *                  description  - Content description
    *                  charset      - Character set to use
    * @access public
    */
    public function __construct($body = '', $params = array())
    {
        if (!defined('MAIL_MIMEPART_CRLF')) {
            define('MAIL_MIMEPART_CRLF', defined('MAIL_MIME_CRLF') ? MAIL_MIME_CRLF : "\r\n", true);
        }

        foreach ($params as $key => $value) {
            switch ($key) {
                case 'content_type':
                    $headers['Content-Type'] = $value . (isset($charset) ? '; charset="' . $charset . '"' : '');
                    break;

                case 'encoding':
                    $this->encoding = $value;
                    $headers['Content-Transfer-Encoding'] = $value;
                    break;

                case 'cid':
                    $headers['Content-ID'] = '<' . $value . '>';
                    break;

                case 'disposition':
                    $headers['Content-Disposition'] = $value . (isset($dfilename) ? '; filename="' . $dfilename . '"' : '');
                    break;

                case 'dfilename':
                    if (isset($headers['Content-Disposition'])) {
                        $headers['Content-Disposition'] .= '; filename="' . $value . '"';
                    } else {
                        $dfilename = $value;
                    }
                    break;

                case 'description':
                    $headers['Content-Description'] = $value;
                    break;

                case 'charset':
                    if (isset($headers['Content-Type'])) {
                        $headers['Content-Type'] .= '; charset="' . $value . '"';
                    } else {
                        $charset = $value;
                    }
                    break;
            }
        }

        // Default content-type
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'text/plain';
        }

        // Default encoding
        if (!isset($this->encoding)) {
            $this->encoding = '7bit';
        }

        // Assign stuff to member variables
        $this->encoded  = array();
        $this->headers  = $headers;
        $this->body     = $body;
    }

    /**
    * Encodes and returns the email. Also stores
    * it in the encoded member variable
    *
    * @return An associative array containing two elements,
    *         body and headers. The headers element is itself
    *         an indexed array.
    */
    public function encode()
    {
        $encoded =& $this->encoded;

        if (!empty($this->subparts)) {
            srand((double)microtime()*1000000);
            $boundary = '=_' . md5(uniqid(rand()) . microtime());
            $this->headers['Content-Type'] .= ';' . MAIL_MIMEPART_CRLF . "\t" . 'boundary="' . $boundary . '"';

            // Add body parts to $subparts
            for ($i = 0; $i < count($this->subparts); $i++) {
                $headers = array();
                $tmp = $this->subparts[$i]->encode();
                foreach ($tmp['headers'] as $key => $value) {
                    $headers[] = $key . ': ' . $value;
                }
                $subparts[] = implode(MAIL_MIMEPART_CRLF, $headers) . MAIL_MIMEPART_CRLF . MAIL_MIMEPART_CRLF . $tmp['body'];
            }

            $encoded['body'] = '--' . $boundary . MAIL_MIMEPART_CRLF .
                               implode('--' . $boundary . MAIL_MIMEPART_CRLF, $subparts) .
                               '--' . $boundary.'--' . MAIL_MIMEPART_CRLF;
        } else {
            $encoded['body'] = $this->getEncodedData($this->body, $this->encoding) . MAIL_MIMEPART_CRLF;
        }

        // Add headers to $encoded
        $encoded['headers'] =& $this->headers;

        return $encoded;
    }

    /**
    * Adds a subpart to current mime part and returns
    * a reference to it
    *
    * @param $body   The body of the subpart, if any.
    * @param $params The parameters for the subpart, same
    *                as the $params argument for constructor.
    * @return A reference to the part you just added.
    */
    public function addSubPart($body, $params)
    {
        $this->subparts[] = new XLS_MIMEPart($body, $params);
        
        return $this->subparts[count($this->subparts) - 1];
    }

    /**
    * Returns encoded data based upon encoding passed to it
    *
    * @param $data     The data to encode.
    * @param $encoding The encoding type to use, 7bit, base64,
    *                  or quoted-printable.
    */
    private function getEncodedData($data, $encoding)
    {
        switch ($encoding) {
            case '8bit':
            case '7bit':
                return $data;
                break;

            case 'quoted-printable':
                return $this->quotedPrintableEncode($data);
                break;

            case 'base64':
                return rtrim(chunk_split(base64_encode($data), 76, MAIL_MIMEPART_CRLF));
                break;

            default:
                return $data;
        }
    }

    /**
    * Encodes data to quoted-printable standard.
    *
    * @param $input    The data to encode
    * @param $line_max Optional max line length. Should
    *                  not be more than 76 chars
    */
    private function quotedPrintableEncode($input , $line_max = 76)
    {
        $lines  = preg_split("/\r?\n/", $input);
        $eol    = MAIL_MIMEPART_CRLF;
        $escape = '=';
        $output = '';

        while(list(, $line) = each($lines)){

            $linlen     = strlen($line);
            $newline = '';

            for ($i = 0; $i < $linlen; $i++) {
                $char = substr($line, $i, 1);
                $dec  = ord($char);

                if (($dec == 32) AND ($i == ($linlen - 1))){    // convert space at eol only
                    $char = '=20';

                } elseif($dec == 9) {
                    ; // Do nothing if a tab.
                } elseif(($dec == 61) OR ($dec < 32 ) OR ($dec > 126)) {
                    $char = $escape . strtoupper(sprintf('%02s', dechex($dec)));
                }

                if ((strlen($newline) + strlen($char)) >= $line_max) {        // MAIL_MIMEPART_CRLF is not counted
                    $output  .= $newline . $escape . $eol;                    // soft line break; " =\r\n" is okay
                    $newline  = '';
                }
                $newline .= $char;
            } // end of for
            $output .= $newline . $eol;
        }
        $output = substr($output, 0, -1 * strlen($eol)); // Don't want last crlf
        return $output;
    }
} // End of class



class XLS_RFC822
{
    /**
     * The address being parsed by the RFC822 object.
     * @private string $address
     */
    private $address = '';

    /**
     * The default domain to use for unqualified addresses.
     * @private string $default_domain
     */
    private $default_domain = 'localhost';

    /**
     * Should we return a nested array showing groups, or flatten everything?
     * @private boolean $nestGroups
     */
    private $nestGroups = true;

    /**
     * Whether or not to validate atoms for non-ascii characters.
     * @private boolean $validate
     */
    private $validate = true;

    /**
     * The array of raw addresses built up as we parse.
     * @private array $addresses
     */
    private $addresses = array();

    /**
     * The final array of parsed address information that we build up.
     * @private array $structure
     */
    private $structure = array();

    /**
     * The current error message, if any.
     * @private string $error
     */
    private $error = null;

    /**
     * An internal counter/pointer.
     * @private integer $index
     */
    private $index = null;

    /**
     * The number of groups that have been found in the address list.
     * @private integer $num_groups
     * @access public
     */
    private $num_groups = 0;

    /**
     * A variable so that we can tell whether or not we're inside a
     * XLS_RFC822 object.
     * @private boolean $mailRFC822
     */
    private $mailRFC822 = true;
    
    /**
    * A limit after which processing stops
    * @private int $limit
    */
    private $limit = null;


    /**
     * Sets up the object. The address must either be set here or when
     * calling parseAddressList(). One or the other.
     *
     * @access public
     * @param string  $address         The address(es) to validate.
     * @param string  $default_domain  Default domain/host etc. If not supplied, will be set to localhost.
     * @param boolean $nest_groups     Whether to return the structure with groups nested for easier viewing.
     * @param boolean $validate        Whether to validate atoms. Turn this off if you need to run addresses through before encoding the personal names, for instance.
     * 
     * @return object XLS_RFC822 A new XLS_RFC822 object.
     */
    function __construct($address = null, $default_domain = null, $nest_groups = null, $validate = null, $limit = null)
    {
        if (isset($address))        $this->address        = $address;
        if (isset($default_domain)) $this->default_domain = $default_domain;
        if (isset($nest_groups))    $this->nestGroups     = $nest_groups;
        if (isset($validate))       $this->validate       = $validate;
        if (isset($limit))          $this->limit          = $limit;
    }


    /**
     * Starts the whole process. The address must either be set here
     * or when creating the object. One or the other.
     *
     * @access public
     * @param string  $address         The address(es) to validate.
     * @param string  $default_domain  Default domain/host etc.
     * @param boolean $nest_groups     Whether to return the structure with groups nested for easier viewing.
     * @param boolean $validate        Whether to validate atoms. Turn this off if you need to run addresses through before encoding the personal names, for instance.
     * 
     * @return array A structured array of addresses.
     */
    public function parseAddressList($address = null, $default_domain = null, $nest_groups = null, $validate = null, $limit = null)
    {

        if (!isset($this->mailRFC822)) {
            $obj = new XLS_RFC822($address, $default_domain, $nest_groups, $validate, $limit);
            return $obj->parseAddressList();
        }

        if (isset($address))        $this->address        = $address;
        if (isset($default_domain)) $this->default_domain = $default_domain;
        if (isset($nest_groups))    $this->nestGroups     = $nest_groups;
        if (isset($validate))       $this->validate       = $validate;
        if (isset($limit))          $this->limit          = $limit;

        $this->structure  = array();
        $this->addresses  = array();
        $this->error      = null;
        $this->index      = null;

        while ($this->address = $this->_splitAddresses($this->address)) {
            continue;
        }
        
        if ($this->address === false || isset($this->error)) {
            return false;
        }

        // Reset timer since large amounts of addresses can take a long time to
        // get here
        set_time_limit(30);

        // Loop through all the addresses
        for ($i = 0; $i < count($this->addresses); $i++){

            if (($return = $this->_validateAddress($this->addresses[$i])) === false
                || isset($this->error)) {
                return false;
            }
            
            if (!$this->nestGroups) {
                $this->structure = array_merge($this->structure, $return);
            } else {
                $this->structure[] = $return;
            }
        }

        return $this->structure;
    }

    /**
     * Splits an address into seperate addresses.
     * 
     * @access private
     * @param string $address The addresses to split.
     * @return boolean Success or failure.
     */
    function _splitAddresses($address)
    {

        if (!empty($this->limit) AND count($this->addresses) == $this->limit) {
            return '';
        }

        if ($this->_isGroup($address) && !isset($this->error)) {
            $split_char = ';';
            $is_group   = true;
        } elseif (!isset($this->error)) {
            $split_char = ',';
            $is_group   = false;
        } elseif (isset($this->error)) {
            return false;
        }

        // Split the string based on the above ten or so lines.
        $parts  = explode($split_char, $address);
        $string = $this->_splitCheck($parts, $split_char);

        // If a group...
        if ($is_group) {
            // If $string does not contain a colon outside of
            // brackets/quotes etc then something's fubar.

            // First check there's a colon at all:
            if (strpos($string, ':') === false) {
                $this->error = 'Invalid address: ' . $string;
                return false;
            }

            // Now check it's outside of brackets/quotes:
            if (!$this->_splitCheck(explode(':', $string), ':'))
                return false;

            // We must have a group at this point, so increase the counter:
            $this->num_groups++;
        }

        // $string now contains the first full address/group.
        // Add to the addresses array.
        $this->addresses[] = array(
                                   'address' => trim($string),
                                   'group'   => $is_group
                                   );

        // Remove the now stored address from the initial line, the +1
        // is to account for the explode character.
        $address = trim(substr($address, strlen($string) + 1));

        // If the next char is a comma and this was a group, then
        // there are more addresses, otherwise, if there are any more
        // chars, then there is another address.
        if ($is_group && substr($address, 0, 1) == ','){
            $address = trim(substr($address, 1));
            return $address;

        } elseif (strlen($address) > 0) {
            return $address;

        } else {
            return '';
        }

        // If you got here then something's off
        return false;
    }

    /**
     * Checks for a group at the start of the string.
     * 
     * @access private
     * @param string $address The address to check.
     * @return boolean Whether or not there is a group at the start of the string.
     */
    function _isGroup($address)
    {
        // First comma not in quotes, angles or escaped:
        $parts  = explode(',', $address);
        $string = $this->_splitCheck($parts, ',');

        // Now we have the first address, we can reliably check for a
        // group by searching for a colon that's not escaped or in
        // quotes or angle brackets.
        if (count($parts = explode(':', $string)) > 1) {
            $string2 = $this->_splitCheck($parts, ':');
            return ($string2 !== $string);
        } else {
            return false;
        }
    }

    /**
     * A common function that will check an exploded string.
     * 
     * @access private
     * @param array $parts The exloded string.
     * @param string $char  The char that was exploded on.
     * @return mixed False if the string contains unclosed quotes/brackets, or the string on success.
     */
    function _splitCheck($parts, $char)
    {
        $string = $parts[0];

        for ($i = 0; $i < count($parts); $i++) {
            if ($this->_hasUnclosedQuotes($string)
                || $this->_hasUnclosedBrackets($string, '<>')
                || $this->_hasUnclosedBrackets($string, '[]')
                || $this->_hasUnclosedBrackets($string, '()')
                || substr($string, -1) == '\\') {
                if (isset($parts[$i + 1])) {
                    $string = $string . $char . $parts[$i + 1];
                } else {
                    $this->error = 'Invalid address spec. Unclosed bracket or quotes';
                    return false;
                }
            } else {
                $this->index = $i;
                break;
            }
        }

        return $string;
    }

    /**
     * Checks if a string has an unclosed quotes or not.
     * 
     * @access private
     * @param string $string The string to check.
     * @return boolean True if there are unclosed quotes inside the string, false otherwise.
     */
    function _hasUnclosedQuotes($string)
    {
        $string     = explode('"', $string);
        $string_cnt = count($string);

        for ($i = 0; $i < (count($string) - 1); $i++)
            if (substr($string[$i], -1) == '\\')
                $string_cnt--;

        return ($string_cnt % 2 === 0);
    }

    /**
     * Checks if a string has an unclosed brackets or not. IMPORTANT:
     * This function handles both angle brackets and square brackets;
     * 
     * @access private
     * @param string $string The string to check.
     * @param string $chars  The characters to check for.
     * @return boolean True if there are unclosed brackets inside the string, false otherwise.
     */
    function _hasUnclosedBrackets($string, $chars)
    {
        $num_angle_start = substr_count($string, $chars[0]);
        $num_angle_end   = substr_count($string, $chars[1]);

        $this->_hasUnclosedBracketsSub($string, $num_angle_start, $chars[0]);
        $this->_hasUnclosedBracketsSub($string, $num_angle_end, $chars[1]);

        if ($num_angle_start < $num_angle_end) {
            $this->error = 'Invalid address spec. Unmatched quote or bracket (' . $chars . ')';
            return false;
        } else {
            return ($num_angle_start > $num_angle_end);
        }
    }

    /**
     * Sub function that is used only by hasUnclosedBrackets().
     * 
     * @access private
     * @param string $string The string to check.
     * @param integer &$num    The number of occurences.
     * @param string $char   The character to count.
     * @return integer The number of occurences of $char in $string, adjusted for backslashes.
     */
    function _hasUnclosedBracketsSub($string, &$num, $char)
    {
        $parts = explode($char, $string);
        for ($i = 0; $i < count($parts); $i++){
            if (substr($parts[$i], -1) == '\\' || $this->_hasUnclosedQuotes($parts[$i]))
                $num--;
            if (isset($parts[$i + 1]))
                $parts[$i + 1] = $parts[$i] . $char . $parts[$i + 1];
        }
        
        return $num;
    }

    /**
     * Function to begin checking the address.
     *
     * @access private
     * @param string $address The address to validate.
     * @return mixed False on failure, or a structured array of address information on success.
     */
    function _validateAddress($address)
    {
        $is_group = false;

        if ($address['group']) {
            $is_group = true;

            // Get the group part of the name
            $parts     = explode(':', $address['address']);
            $groupname = $this->_splitCheck($parts, ':');
            $structure = array();

            // And validate the group part of the name.
            if (!$this->_validatePhrase($groupname)){
                $this->error = 'Group name did not validate.';
                return false;
            } else {
                // Don't include groups if we are not nesting
                // them. This avoids returning invalid addresses.
                if ($this->nestGroups) {
                    $structure = new stdClass;
                    $structure->groupname = $groupname;
                }
            }

            $address['address'] = ltrim(substr($address['address'], strlen($groupname . ':')));
        }

        // If a group then split on comma and put into an array.
        // Otherwise, Just put the whole address in an array.
        if ($is_group) {
            while (strlen($address['address']) > 0) {
                $parts       = explode(',', $address['address']);
                $addresses[] = $this->_splitCheck($parts, ',');
                $address['address'] = trim(substr($address['address'], strlen(end($addresses) . ',')));
            }
        } else {
            $addresses[] = $address['address'];
        }

        // Check that $addresses is set, if address like this:
        // Groupname:;
        // Then errors were appearing.
        if (!isset($addresses)){
            $this->error = 'Empty group.';
            return false;
        }

        for ($i = 0; $i < count($addresses); $i++) {
            $addresses[$i] = trim($addresses[$i]);
        }

        // Validate each mailbox.
        // Format could be one of: name <geezer@domain.com>
        //                         geezer@domain.com
        //                         geezer
        // ... or any other format valid by RFC 822.
        array_walk($addresses, array($this, 'validateMailbox'));

        // Nested format
        if ($this->nestGroups) {
            if ($is_group) {
                $structure->addresses = $addresses;
            } else {
                $structure = $addresses[0];
            }

        // Flat format
        } else {
            if ($is_group) {
                $structure = array_merge($structure, $addresses);
            } else {
                $structure = $addresses;
            }
        }

        return $structure;
    }

    /**
     * Function to validate a phrase.
     *
     * @access private
     * @param string $phrase The phrase to check.
     * @return boolean Success or failure.
     */
    function _validatePhrase($phrase)
    {
        // Splits on one or more Tab or space.
        $parts = preg_split('/[ \\x09]+/', $phrase, -1, PREG_SPLIT_NO_EMPTY);

        $phrase_parts = array();
        while (count($parts) > 0){
            $phrase_parts[] = $this->_splitCheck($parts, ' ');
            for ($i = 0; $i < $this->index + 1; $i++)
                array_shift($parts);
        }

        for ($i = 0; $i < count($phrase_parts); $i++) {
            // If quoted string:
            if (substr($phrase_parts[$i], 0, 1) == '"') {
                if (!$this->_validateQuotedString($phrase_parts[$i]))
                    return false;
                continue;
            }

            // Otherwise it's an atom:
            if (!$this->_validateAtom($phrase_parts[$i])) return false;
        }

        return true;
    }

    /**
     * Function to validate an atom which from rfc822 is:
     * atom = 1*<any CHAR except specials, SPACE and CTLs>
     * 
     * If validation ($this->validate) has been turned off, then
     * validateAtom() doesn't actually check anything. This is so that you
     * can split a list of addresses up before encoding personal names
     * (umlauts, etc.), for example.
     * 
     * @access private
     * @param string $atom The string to check.
     * @return boolean Success or failure.
     */
    function _validateAtom($atom)
    {
        if (!$this->validate) {
            // Validation has been turned off; assume the atom is okay.
            return true;
        }

        // Check for any char from ASCII 0 - ASCII 127
        if (!preg_match('/^[\\x00-\\x7E]+$/i', $atom, $matches)) {
            return false;
        }

        // Check for specials:
        if (preg_match('/[][()<>@,;\\:". ]/', $atom)) {
            return false;
        }

        // Check for control characters (ASCII 0-31):
        if (preg_match('/[\\x00-\\x1F]+/', $atom)) {
            return false;
        }

        return true;
    }

    /**
     * Function to validate quoted string, which is:
     * quoted-string = <"> *(qtext/quoted-pair) <">
     * 
     * @access private
     * @param string $qstring The string to check
     * @return boolean Success or failure.
     */
    function _validateQuotedString($qstring)
    {
        // Leading and trailing "
        $qstring = substr($qstring, 1, -1);

        // Perform check.
        return !(preg_match('/(.)[\x0D\\\\"]/', $qstring, $matches) && $matches[1] != '\\');
    }

    /**
     * Function to validate a mailbox, which is:
     * mailbox =   addr-spec         ; simple address
     *           / phrase route-addr ; name and route-addr
     * 
     * @access public
     * @param string &$mailbox The string to check.
     * @return boolean Success or failure.
     */
    function validateMailbox(&$mailbox)
    {
        // A couple of defaults.
        $phrase  = '';
        $comment = '';

        // Catch any RFC822 comments and store them separately
        $_mailbox = $mailbox;
        while (strlen(trim($_mailbox)) > 0) {
            $parts = explode('(', $_mailbox);
            $before_comment = $this->_splitCheck($parts, '(');
            if ($before_comment != $_mailbox) {
                // First char should be a (
                $comment    = substr(str_replace($before_comment, '', $_mailbox), 1);
                $parts      = explode(')', $comment);
                $comment    = $this->_splitCheck($parts, ')');
                $comments[] = $comment;

                // +1 is for the trailing )
                $_mailbox   = substr($_mailbox, strpos($_mailbox, $comment)+strlen($comment)+1);
            } else {
                break;
            }
        }

        for($i=0; $i<count(@$comments); $i++){
            $mailbox = str_replace('('.$comments[$i].')', '', $mailbox);
        }
        $mailbox = trim($mailbox);

        // Check for name + route-addr
        if (substr($mailbox, -1) == '>' && substr($mailbox, 0, 1) != '<') {
            $parts  = explode('<', $mailbox);
            $name   = $this->_splitCheck($parts, '<');

            $phrase     = trim($name);
            $route_addr = trim(substr($mailbox, strlen($name.'<'), -1));

            if ($this->_validatePhrase($phrase) === false || ($route_addr = $this->_validateRouteAddr($route_addr)) === false)
                return false;

        // Only got addr-spec
        } else {
            // First snip angle brackets if present.
            if (substr($mailbox,0,1) == '<' && substr($mailbox,-1) == '>')
                $addr_spec = substr($mailbox,1,-1);
            else
                $addr_spec = $mailbox;

            if (($addr_spec = $this->_validateAddrSpec($addr_spec)) === false)
                return false;
        }

        // Construct the object that will be returned.
        $mbox = new stdClass();

        // Add the phrase (even if empty) and comments
        $mbox->personal = $phrase;
        $mbox->comment  = isset($comments) ? $comments : array();

        if (isset($route_addr)) {
            $mbox->mailbox = $route_addr['local_part'];
            $mbox->host    = $route_addr['domain'];
            $route_addr['adl'] !== '' ? $mbox->adl = $route_addr['adl'] : '';
        } else {
            $mbox->mailbox = $addr_spec['local_part'];
            $mbox->host    = $addr_spec['domain'];
        }

        $mailbox = $mbox;
        return true;
    }

    /**
     * This function validates a route-addr which is:
     * route-addr = "<" [route] addr-spec ">"
     *
     * Angle brackets have already been removed at the point of
     * getting to this function.
     * 
     * @access private
     * @param string $route_addr The string to check.
     * @return mixed False on failure, or an array containing validated address/route information on success.
     */
    function _validateRouteAddr($route_addr)
    {
        // Check for colon.
        if (strpos($route_addr, ':') !== false) {
            $parts = explode(':', $route_addr);
            $route = $this->_splitCheck($parts, ':');
        } else {
            $route = $route_addr;
        }

        // If $route is same as $route_addr then the colon was in
        // quotes or brackets or, of course, non existent.
        if ($route === $route_addr){
            unset($route);
            $addr_spec = $route_addr;
            if (($addr_spec = $this->_validateAddrSpec($addr_spec)) === false) {
                return false;
            }
        } else {
            // Validate route part.
            if (($route = $this->_validateRoute($route)) === false) {
                return false;
            }

            $addr_spec = substr($route_addr, strlen($route . ':'));

            // Validate addr-spec part.
            if (($addr_spec = $this->_validateAddrSpec($addr_spec)) === false) {
                return false;
            }
        }

        if (isset($route)) {
            $return['adl'] = $route;
        } else {
            $return['adl'] = '';
        }

        $return = array_merge($return, $addr_spec);
        return $return;
    }

    /**
     * Function to validate a route, which is:
     * route = 1#("@" domain) ":"
     * 
     * @access private
     * @param string $route The string to check.
     * @return mixed False on failure, or the validated $route on success.
     */
    function _validateRoute($route)
    {
        // Split on comma.
        $domains = explode(',', trim($route));

        for ($i = 0; $i < count($domains); $i++) {
            $domains[$i] = str_replace('@', '', trim($domains[$i]));
            if (!$this->_validateDomain($domains[$i])) return false;
        }

        return $route;
    }

    /**
     * Function to validate a domain, though this is not quite what
     * you expect of a strict internet domain.
     *
     * domain = sub-domain *("." sub-domain)
     * 
     * @access private
     * @param string $domain The string to check.
     * @return mixed False on failure, or the validated domain on success.
     */
    function _validateDomain($domain)
    {
        // Note the different use of $subdomains and $sub_domains                        
        $subdomains = explode('.', $domain);

        while (count($subdomains) > 0) {
            $sub_domains[] = $this->_splitCheck($subdomains, '.');
            for ($i = 0; $i < $this->index + 1; $i++)
                array_shift($subdomains);
        }

        for ($i = 0; $i < count($sub_domains); $i++) {
            if (!$this->_validateSubdomain(trim($sub_domains[$i])))
                return false;
        }

        // Managed to get here, so return input.
        return $domain;
    }

    /**
     * Function to validate a subdomain:
     *   subdomain = domain-ref / domain-literal
     * 
     * @access private
     * @param string $subdomain The string to check.
     * @return boolean Success or failure.
     */
    function _validateSubdomain($subdomain)
    {
        if (preg_match('|^\[(.*)]$|', $subdomain, $arr)){
            if (!$this->_validateDliteral($arr[1])) return false;
        } else {
            if (!$this->_validateAtom($subdomain)) return false;
        }

        // Got here, so return successful.
        return true;
    }

    /**
     * Function to validate a domain literal:
     *   domain-literal =  "[" *(dtext / quoted-pair) "]"
     * 
     * @access private
     * @param string $dliteral The string to check.
     * @return boolean Success or failure.
     */
    function _validateDliteral($dliteral)
    {
        return !preg_match('/(.)[][\x0D\\\\]/', $dliteral, $matches) && $matches[1] != '\\';
    }

    /**
     * Function to validate an addr-spec.
     *
     * addr-spec = local-part "@" domain
     * 
     * @access private
     * @param string $addr_spec The string to check.
     * @return mixed False on failure, or the validated addr-spec on success.
     */
    function _validateAddrSpec($addr_spec)
    {
        $addr_spec = trim($addr_spec);

        // Split on @ sign if there is one.
        if (strpos($addr_spec, '@') !== false) {
            $parts      = explode('@', $addr_spec);
            $local_part = $this->_splitCheck($parts, '@');
            $domain     = substr($addr_spec, strlen($local_part . '@'));

        // No @ sign so assume the default domain.
        } else {
            $local_part = $addr_spec;
            $domain     = $this->default_domain;
        }

        if (($local_part = $this->_validateLocalPart($local_part)) === false) return false;
        if (($domain     = $this->_validateDomain($domain)) === false) return false;
        
        // Got here so return successful.
        return array('local_part' => $local_part, 'domain' => $domain);
    }

    /**
     * Function to validate the local part of an address:
     *   local-part = word *("." word)
     * 
     * @access private
     * @param string $local_part
     * @return mixed False on failure, or the validated local part on success.
     */
    function _validateLocalPart($local_part)
    {
        $parts = explode('.', $local_part);

        // Split the local_part into words.
        while (count($parts) > 0){
            $words[] = $this->_splitCheck($parts, '.');
            for ($i = 0; $i < $this->index + 1; $i++) {
                array_shift($parts);
            }
        }

        // Validate each word.
        for ($i = 0; $i < count($words); $i++) {
            if ($this->_validatePhrase(trim($words[$i])) === false) return false;
        }

        // Managed to get here, so return the input.
        return $local_part;
    }

    /**
    * Returns an approximate count of how many addresses are
    * in the given string. This is APPROXIMATE as it only splits
    * based on a comma which has no preceding backslash. Could be
    * useful as large amounts of addresses will end up producing
    * *large* structures when used with parseAddressList().
    *
    * @param  string $data Addresses to count
    * @return int          Approximate count
    */
    function approximateCount($data)
    {
        return count(preg_split('/(?<!\\\\),/', $data));
    }
    
    /**
    * This is a email validating function seperate to the rest
    * of the class. It simply validates whether an email is of
    * the common internet form: <user>@<domain>. This can be
    * sufficient for most people. Optional stricter mode can
    * be utilised which restricts mailbox characters allowed
    * to alphanumeric, full stop, hyphen and underscore.
    *
    * @param  string  $data   Address to check
    * @param  boolean $strict Optional stricter mode
    * @return mixed           False if it fails, an indexed array
    *                         username/domain if it matches
    */
    function isValidInetAddress($data, $strict = false)
    {
        $regex = $strict ? '/^([.0-9a-z_-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i' : '/^([*+!.&#$|\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i';
        if (preg_match($regex, trim($data), $matches)) {
            return array($matches[1], $matches[2]);
        } else {
            return false;
        }
    }
}



define('SMTP_STATUS_NOT_CONNECTED', 1, true);
define('SMTP_STATUS_CONNECTED', 2, true);

class XLSSMTP
{
    private $authenticated;
    private $connection;
    private $recipients;
    private $headers;
    private $timeout;
    private $errors;
    private $status;
    private $body;
    private $from;
    private $host;
    private $port;
    private $helo;
    private $auth;
    private $user;
    private $pass;

    /**
    * Constructor function. Arguments:
    * $params - An assoc array of parameters:
    *
    *   host    - The hostname of the smtp server       Default: localhost
    *   port    - The port the smtp server runs on      Default: 25
    *   helo    - What to send as the HELO command      Default: localhost
    *             (typically the hostname of the
    *             machine this script runs on)
    *   auth    - Whether to use basic authentication   Default: FALSE
    *   user    - Username for authentication           Default: <blank>
    *   pass    - Password for authentication           Default: <blank>
    *   timeout - The timeout in seconds for the call   Default: 5
    *             to fsockopen()
    */
    public function __construct($params = array())
    {
        
        global $_SERVER;

        if(!defined('CRLF'))
            define('CRLF', "\r\n", TRUE);

        $this->authenticated = FALSE;
        $this->timeout       = 5;
        $this->status        = SMTP_STATUS_NOT_CONNECTED;
        $this->host          = 'localhost';
        $this->port          = 25;
        $this->helo          = $_SERVER['HTTP_HOST'];
        $this->auth          = FALSE;
        $this->user          = '';
        $this->pass          = '';
        $this->errors        = array();

        foreach($params as $key => $value){
            $this->$key = $value;
        }
    }

    /**
    * Connect function. This will, when called
    * statically, create a new smtp object, 
    * call the connect function (ie this function)
    * and return it. When not called statically,
    * it will connect to the server and send
    * the HELO command.
    */
    public function connect($params = array())
    {
        
            try{
                $this->connection = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
            } catch (Exception $e) {
                _xls_log("Cannot connect to mailserver " . $this->host);
                return;
            }
            if (function_exists('socket_set_timeout')) {
                @socket_set_timeout($this->connection, 5, 0);
            }

            $greeting = $this->get_data();
            if (is_resource($this->connection)) {
                //error_log("connected " . print_r($this, true));
                $this->status = SMTP_STATUS_CONNECTED; // ASHIK was a bug
                return $this->auth ? $this->ehlo() : $this->helo();
            } else {
                $this->errors[] = 'Failed to connect to server: '.$errstr;
                return FALSE;
            }
            
            
    }

    /**
    * Function which handles sending the mail.
    * Arguments:
    * $params   - Optional assoc array of parameters.
    *            Can contain:
    *              recipients - Indexed array of recipients
    *              from       - The from address. (used in MAIL FROM:),
    *                           this will be the return path
    *              headers    - Indexed array of headers, one header per array entry
    *              body       - The body of the email
    *            It can also contain any of the parameters from the connect()
    *            function
    */
    public function send($params = array())
    {
        foreach ($params as $key => $value) {
            $this->set($key, $value);
        }

        if ($this->is_connected()) {

            // Do we auth or not? Note the distinction between the auth variable and auth() function
            if ($this->auth AND !$this->authenticated) {
                if(!$this->auth())
                    return false;
            }

            $this->mail($this->from);
            
            if (is_array($this->recipients)) {
                foreach ($this->recipients as $value) {
                    $this->rcpt($value);
                }
            } else {
                $this->rcpt($this->recipients);
            }

            if (!$this->data()) {
                return false;
            }

            // Transparency
            $headers = str_replace(CRLF.'.', CRLF.'..', trim(implode(CRLF, $this->headers)));
            $body    = str_replace(CRLF.'.', CRLF.'..', $this->body);
            $body    = substr($body, 0, 1) == '.' ? '.'.$body : $body;

            $this->send_data($headers);
            $this->send_data('');
            $this->send_data($body);
            $this->send_data('.');

            $result = (substr(trim($this->get_data()), 0, 3) === '250');
            //$this->rset();
            return $result;
        } else {
            $this->errors[] = 'Not connected!';
            return FALSE;
        }
    }
    
    /**
    * Function to implement HELO cmd
    */
    private function helo()
    {
        if(is_resource($this->connection)
                AND $this->send_data('HELO '.$this->helo)
                AND substr(trim($error = $this->get_data()), 0, 3) === '250' ){

            return true;

        } else {
            $this->errors[] = 'HELO command failed, output: ' . trim(substr(trim($error),3));
            return false;
        }
    }
    
    /**
    * Function to implement EHLO cmd
    */
    private function ehlo()
    {
        if (is_resource($this->connection)
                AND $this->send_data('EHLO '.$this->helo)
                AND substr(trim($error = $this->get_data()), 0, 3) === '250' ){

            return true;

        } else {
            $this->errors[] = 'EHLO command failed, output: ' . trim(substr(trim($error),3));
            return false;
        }
    }
    
    /**
    * Function to implement RSET cmd
    */
    private function rset()
    {
        if (is_resource($this->connection)
                AND $this->send_data('RSET')
                AND substr(trim($error = $this->get_data()), 0, 3) === '250' ){

            return true;

        } else {
            $this->errors[] = 'RSET command failed, output: ' . trim(substr(trim($error),3));
            return false;
        }
    }

    /**
    * Function to implement QUIT cmd
    */
    private function quit()
    {
        if(is_resource($this->connection)
                AND $this->send_data('QUIT')
                AND substr(trim($error = $this->get_data()), 0, 3) === '221' ){

            fclose($this->connection);
            $this->status = SMTP_STATUS_NOT_CONNECTED;
            return true;

        } else {
            $this->errors[] = 'QUIT command failed, output: ' . trim(substr(trim($error),3));
            return false;
        }
    }
    
    /**
    * Function to implement AUTH cmd
    */
    private function auth()
    {
        if (is_resource($this->connection)
                AND $this->send_data('AUTH LOGIN')
                AND substr(trim($error = $this->get_data()), 0, 3) === '334'
                AND $this->send_data(base64_encode($this->user))            // Send username
                AND substr(trim($error = $this->get_data()),0,3) === '334'
                AND $this->send_data(base64_encode($this->pass))            // Send password
                AND substr(trim($error = $this->get_data()),0,3) === '235' ){

            $this->authenticated = true;
            return true;

        } else {
            $this->errors[] = 'AUTH command failed: ' . trim(substr(trim($error),3));
            return false;
        }
    }

    /**
    * Function that handles the MAIL FROM: cmd
    */
    private function mail($from)
    {
        if ($this->is_connected()
            AND $this->send_data('MAIL FROM:<'.$from.'>')
            AND substr(trim($this->get_data()), 0, 2) === '250' ) {

            return true;

        } else {
            return false;
        }
    }

    /**
    * Function that handles the RCPT TO: cmd
    */
    private function rcpt($to)
    {
        if($this->is_connected()
            AND $this->send_data('RCPT TO:<'.$to.'>')
            AND substr(trim($error = $this->get_data()), 0, 2) === '25' ){

            return true;

        } else {
            $this->errors[] = trim(substr(trim($error), 3));
            return false;
        }
    }

    /**
    * Function that sends the DATA cmd
    */
    private function data()
    {
        if($this->is_connected()
            AND $this->send_data('DATA')
            AND substr(trim($error = $this->get_data()), 0, 3) === '354' ) {

            return true;

        } else {
            $this->errors[] = trim(substr(trim($error), 3));
            return false;
        }
    }

    /**
    * Function to determine if this object
    * is connected to the server or not.
    */
    private function is_connected()
    {
        return (is_resource($this->connection) AND ($this->status === SMTP_STATUS_CONNECTED));
    }

    /**
    * Function to send a bit of data
    */
    private function send_data($data)
    {
        if(is_resource($this->connection)){
            return fwrite($this->connection, $data.CRLF, strlen($data)+2);
            
        } else {
            return false;
        }
    }

    /**
    * Function to get data.
    */
    private function get_data()
    {
        $return = '';
        $line   = '';
        $loops  = 0;

        if(is_resource($this->connection)){
            while((strpos($return, CRLF) === FALSE OR substr($line,3,1) !== ' ') AND $loops < 100){
                $line    = fgets($this->connection, 512);
                $return .= $line;
                $loops++;
            }
            return $return;

        }else
            return false;
    }

    /**
    * Sets a variable
    */    
    public function set($var, $value)
    {
        $this->$var = $value;
        return true;
    }
    
    /**
    * Function to return the errors array
    */
    public function getErrors()
    {
        return $this->errors;
    }
    

} // End of class

?> 
