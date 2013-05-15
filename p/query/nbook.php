<?php
require_once( "review.php" );

class NBook{
    private $name = "",
            $isbn = 0,
            $author = array(),
            $publication = "",
            $language = "",
            $description = "",
            $subjects = array();
            
    public function __construct( $dom ){
        if( is_object( $dom ) ){
            $b = $dom->BookList->BookData;
            $this->name = (string)$b->Title;
            $this->isbn = $b->attributes();
            $this->isbn = (string)$this->isbn[ "isbn" ];
            foreach( explode( ",", $b->AuthorsText ) as $name ){
                $name = (string)$name;
                if( $name != "" )
                    array_push( $this->author, strtolower( $name ) );
            }
            $pub = trim( (string)$b->PublisherText );
            if( $pub )
                $this->publication = strtolower( $pub );
            $sum = (string)$b->Summary;
            $this->description = strtolower( $sum);
            foreach( $b->Subjects->Subject as $sub ){
                array_push( $this->subjects, strtolower( (string)$sub ) );
            }
        }
        else{
            throw new Exception( "parameter needs to be a html dom object with simple_html_dom object interfaces" );
        }
    }
    public function getDetails(){
        return array(
                    "name" => $this->name,
                    "author" => $this->author,
                    "publication" => $this->publication,
                    "language" => $this->language,
                    "description" => $this->description,
                    "subjects" => $this->subjects
               );
    }
    public function getDescription(){
        return $this->description;
    }
    public function getEditorialReview(){
        return $this->editorial_review;
    }
    public function getUserReview(){
        return $this->user_review;
    }
    public function getSubjects(){
        return $this->subjects;
    }
}
?>
