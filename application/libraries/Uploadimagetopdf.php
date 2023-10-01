<?php
class Uploadimagetopdf {
    public $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function upload($id, $file, $uploadPath)
    {
        $hitung = count($file['name']);
        $fileArray = [];

        for($i = 0;$i < $hitung; $i++) {
            if($file['name'][$i] != '') {
                $target_dir = "images/temppdf/";
                $target_file = $target_dir . basename($file["name"][$i]);
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                // Check if image file is a actual image or fake image
                if (!empty($file["tmp_name"][$i])) {
                    $check = getimagesize($file["tmp_name"][$i]);
                    if ($check !== false) {
                        // echo "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        // echo "File is not an image.";
                        $uploadOk = 0;
                    }
                }
                // Check if file already exists
                // if (file_exists($target_file)) {
                //     echo "Sorry, file already exists.";
                //     $uploadOk = 0;
                // }
                // Check file size
                if ($file["size"][$i] > 500000) {
                    // echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    // echo "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($file["tmp_name"][$i], $target_file) == true) {
                        // echo "The file ". basename( $file["name"]). " has been uploaded.";
                        if ($file["name"][$i] != '') {
                            //                        $_POST[$FILE] = $file["name"][$i];
                            $fileArray[] = '<img src="images/temppdf/' . basename($file["name"][$i] . '">');
                        }
                    } else {
                        // echo "Sorry, there was an error uploading your file.";
                    }
                }
            }
        }
        if(!empty($fileArray)) {
            $html = implode('<br>', $fileArray);

            $pdfFileName = time() . '.pdf';
            $pdfFilePath = "./uploads/" . $uploadPath . "/" . $id . "/" . $pdfFileName;

            $this->CI->load->library('pdf');
            $pdf = $this->CI->pdf->load();
            $pdf->SetFooter('|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
            $pdf->WriteHTML($html); // write the HTML into the PDF
            $pdf->Output($pdfFilePath, 'F'); // save to file because we can

            for ($i = 0; $i < $hitung; $i++) {
                if($file['name'][$i] != '') {
                    unlink('images/temppdf/' . $file['name'][$i]);
                }
            }
        }else{
            $pdfFileName = false;
        }

        return @$pdfFileName;
    }
}