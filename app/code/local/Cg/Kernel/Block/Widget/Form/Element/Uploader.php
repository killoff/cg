<?php
class Cg_Kernel_Block_Widget_Form_Element_Uploader extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        return '
            <button id="selectButton">Выбрать файлы...</button>
            <ul id="manualUploadModeExample" class="unstyled"></ul>
            <button id="triggerUpload">Загрузить!</button>

            <!--div class="">

            <div style="float: left;padding: 5px;"><img src="" alt="" style="width: 200px;"></div>
            <div style="float: left;padding: 5px;"><img src="" alt="" style="width: 200px;"></div>

            </div-->

    <script type="text/javascript">
    $j(document).ready(function() {
    var errorHandler = function(event, id, fileName, reason) {
        qq.log("id: " + id + ", fileName: " + fileName + ", reason: " + reason);
    };


    var fileNum = 0;
    var uploaded = 0;
    var completed = 0;
    $j("#manualUploadModeExample").fineUploader({
        autoUpload: false,
        uploadButtonText: "sdfsdf",
        request: {
        endpoint: "/catalog/?action=upload_gallery&i="
        },
        display: {
        fileSizeOnSubmit: true
        },
        button: $j("#selectButton"),
        validation: {
        allowedExtensions: ["jpeg", "jpg", "png"],
            sizeLimit: 500000,
            minSizeLimit: 20000
        }
    })
        .on("error", errorHandler)
        .on("upload", function(event, id, filename) {
            uploaded++;
        })
        .on("complete", function(event, id, filename) {
            completed++;
            if (completed == uploaded) {
                location.reload();
            }
        });

        $j("#triggerUpload").click(function() {
            $j("#manualUploadModeExample").fineUploader("uploadStoredFiles");
        });
    });
    </script>

    ';
        return parent::getElementHtml();
    }
}
