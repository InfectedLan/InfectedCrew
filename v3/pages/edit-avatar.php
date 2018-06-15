<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2017 Infected <http://infected.no/>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'session.php';
require_once 'handlers/restrictedpagehandler.php';
require_once 'utils/crewutils.php';
require_once 'page.php';

class EditAvatarPage extends Page {
    public function canAccess(User $user): bool {
        return true;
    }
    public function isPublic(): bool {
        return false;
    }
	public function getTitle(): ?string {
		return 'Avatar-instillinger';
	}

    private function parse_size($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }

    public function getContent(User $user = null): string {
        $content = null;
        //Add an extra footer to each page
        $width = Settings::avatar_minimum_width;
        $height = Settings::avatar_minimum_height;

        $recommendedWidth = Settings::avatar_hd_w;
        $recommendedHeight = Settings::avatar_hd_h;

        //Fetch max upload size
        $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = $this->parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
            $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = $this->parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        $max_size = $max_size/1024/1024;
        $avatarInfo = <<<EOD
<div class="box box-primary">
<div class="box-header with-border">
  <h3 class="box-title">Avatar-krav og informasjon</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<form role="form">
    <div class="box-body">
        <ul>
            <li>Ansiktet skal være synlig og motivet i bildet - tenk slack passfoto</li>
            <li>Minimumsstørrelse: $width x $height, men helst større enn $recommendedWidth x $recommendedHeight !</li>
            <li>Max. filstørrelse: $max_size MB</li>
            <li>Du kan søke selv om avataren din ikke har blitt godkjent enda</li>
            <li>Din søknad beholdes selv om avataren din blir avslått, men det er forventet at du snarest laster opp en ny avatar som bedre følger regningslinjene</li>
        </ul>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <i>Kontakt infected ved videre spørsmål</i>
    </div>
</form>
</div>
EOD;
        $deleteAvatarButton = <<<EOD
<script>
    function deleteAvatar() {
        $.getJSON('../api/json/avatar/removeAvatar.php', function(data) {
            if (data.result) {
                location.reload()
            } else { 
                //console.log(data.message);
                error(data.message);
            }
        });
    }
</script>
<div class="box box-danger">
    <div class="box-header with-border">
      <h3 class="box-title">Slett avatar</h3>
    </div>
<!-- /.box-header -->
<!-- form start -->
    <div class="box-body">
        <p>Du kan slette avataren din om du ikke er fornøyd med den, eller ikke vil at infected skal lagre den lenger. Om du er i et crew, er det ønsket at du i såfall laster opp en ny en snarest.</p>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-danger" onClick="deleteAvatar()">Slett avatar</button>
    </div>
</div>
EOD;

        if($user->hasAvatar()) {
            $avatar = $user->getAvatar();

            switch ($avatar->getState()) {
                case AvatarHandler::STATE_NEW:
                    //Calculate size factor. The crop pane is 800 wide.
                    $temp = explode('.', $avatar->getTemp());
                    $extension = strtolower(end($temp));
                    $image = 0;

                    if ($extension == 'png') {
                        $image = imagecreatefrompng(Settings::dynamic_path . $avatar->getTemp());
                    } else if ($extension == 'jpeg' ||
                        $extension == 'jpg') {
                        $image = imagecreatefromjpeg(Settings::dynamic_path . $avatar->getTemp());
                    } else {
                        // TODO: Handle if the format is not supported here...
                    }

                    $scaleFactor = 800 / imagesx($image);
                    $cropHeight = ($scaleFactor * imagesy($image));

                    $minWidth = (Settings::avatar_minimum_width * $scaleFactor);
                    $minHeight = (Settings::avatar_minimum_height * $scaleFactor);
                    $tmpUrl = $avatar->getTemp();

                    $content = <<<EOD

<div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Beskjær avataren</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form action="../api/json/avatar/cropAvatar.php" method="get" id="cropform" onsubmit="return checkCoords();">
        <div class="box-body">
            <link rel="stylesheet" href="../api/libraries/jcrop/css/jquery.Jcrop.css">
            <script src="../api/libraries/jcrop/js/jquery.Jcrop.js"></script>
            <script>
                $(document).ready(function() {
                    var options = {
                        success: function(responseText, statusText, xhr, $form) {
                            var data = responseText;
                            if (data.result) {
                                location.reload();
                            } else {
                                error(data.message);
                            }
                        }
                    };
                    $("#cropform").ajaxForm(options);
                });
            </script>


            <script>
                $(document).ready(function() {
                    $('#cropCanvas').Jcrop({
                        
                        setSelect:   [ 0, 0, 800, $cropHeight ],
                        aspectRatio: 400/300,
                        minSize: [ $minWidth , $minHeight ],
                        onSelect: updateCoords
                    });
                });
                var base_image = new Image();
                base_image.src='../dynamic/$tmpUrl';
                base_image.onload = function() {
                var canvas = document.getElementById('cropCanvas').getContext('2d'); 
                canvas.drawImage(base_image, 0, 0, 800, $cropHeight);
                };

                function updateCoords(c) {
                    $('#x').val(c.x);
                    $('#y').val(c.y);
                    $('#w').val(c.w);
                    $('#h').val(c.h);
                };

                function checkCoords() {
                    if (parseInt($('#w').val())) return true;
                    alert('Please select a crop region then press submit.');
                    return false;
                };
            </script>
            <canvas width="800" height=" $cropHeight " id="cropCanvas"></canvas>
                    <input type="hidden" id="x" name="x">
                    <input type="hidden" id="y" name="y">
                    <input type="hidden" id="w" name="w">
                    <input type="hidden" id="h" name="h">
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <input type="submit" class="btn btn-primary" value="Lagre">
        </div>
    </form>
</div>
EOD;
                    $content .= $deleteAvatarButton . $avatarInfo;
                break;
                case AvatarHandler::STATE_PENDING:
                    $sdAvatarLocation = $avatar->getSd();
                    $content = <<<EOD

<div class="box box-primary">
<div class="box-header with-border">
  <h3 class="box-title">Venter på godkjenning</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<form role="form">
    <div class="box-body">
        <p>Avataren din venter på at en chief skal godkjenne den. Du kan nå søke crew, så gjør det i mellomtiden.</p>
        <img src=../dynamic/$sdAvatarLocation />
    </div>
</form>
</div>
EOD;
                    $content .= $deleteAvatarButton . $avatarInfo;
                break;
                case AvatarHandler::STATE_REJECTED:
                    $sdAvatarLocation = $avatar->getSd();
                    $content = <<<EOD

<div class="box box-danger">
<div class="box-header with-border">
  <h3 class="box-title">Avslått</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<form role="form">
    <div class="box-body">
        <p>Din avatar har blitt avslått. Les igjennom reglene, slett avataren, og prøv på nytt!</p>
        <img src=../dynamic/$sdAvatarLocation />
    </div>
</form>
</div>
EOD;
                    $content .= $deleteAvatarButton . $avatarInfo;
                break;
                case AvatarHandler::STATE_ACCEPTED:
                    $sdAvatarLocation = $avatar->getSd();
                    $content = <<<EOD

<div class="box box-success">
<div class="box-header with-border">
  <h3 class="box-title">Godkjent</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<form role="form">
    <div class="box-body">
        <img src=../dynamic/$sdAvatarLocation />
    </div>
</form>
</div>
EOD;
                    $content .= $deleteAvatarButton . $avatarInfo;
                break;
                default:
                    $content = <<<EOD

<div class="box box-primary">
<div class="box-header with-border">
  <h3 class="box-title">Ukjent state</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<form role="form">
    <div class="box-body">
        <p>Avataren din er i en ukjent state</p>
        <i>Kontakt infected</i>
    </div>
</form>
</div>
EOD;
                break;
            }
        } else {
            $content = <<<EOD
<script>
$(document).ready(function() {
    $("#file").change(function() {
        console.log("File is selected");
        form = $("#uploadForm");
        form.submit();
        //$("#uploadForm").fadeOut();
    });
    var options = {
        beforeSubmit: function(form, options) {
            console.log("before submit");
        },
        success: function(responseText, statusText, xhr, $form) {
            var data = responseText;
            console.log(data);
            if (data.result) {
                location.reload();
            } else {
                error(data.message);
                //$("#uploadForm").fadeIn();
            }
        }
    };
    $("#uploadForm").ajaxForm(options);

    $('.avatar-dropzone').on("dragenter dragstart dragend dragleave dragover drag drop", function (e) {
        e.preventDefault();
    });
    $('.avatar-dropzone').on('dragover dragenter', function() {
        $('.avatar-dropzone').addClass('avatar-dragover');
    })
    $('.avatar-dropzone').on('dragleave dragend drop', function() {
        $('.avatar-dropzone').removeClass('avatar-dragover');
    })

});
function onDrop(data) {
    data.preventDefault();
    console.log(data);
    //$("#file").files = data.dataTransfer.files;
    $("#file").prop("files", data.dataTransfer.files);

    if (data.dataTransfer.items) {
        // Use DataTransferItemList interface to remove the drag data
        data.dataTransfer.items.clear();
    } else {
        // Use DataTransfer interface to remove the drag data
        data.dataTransfer.clearData();
    }
}
</script>
<div class="box box-primary">
<div class="box-header with-border">
  <h3 class="box-title">Last opp avatar</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<div class="box-body">
    <div class="avatar-dropzone" ondrop="onDrop(event);">
        <form role="form" action="../api/json/avatar/uploadAvatar.php" method="post" id="uploadForm" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="15728640" />
            <input type="file" name="file" id="file">
            <input type="submit" value="go" />
        </form>
        <label for="file">
        <i class="fa fa-upload"></i>
        <p>Klikk her, eller dra en fil hit for å laste opp</p></label>
    </div>
</div>
</div>
EOD;
            $content .= $avatarInfo;

        }
          
		return $content;
	}
}