<div id="wrapperFormAdd">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savehutang" enctype="multipart/form-data">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-sm-12">
                        <p class="form-control-static"><img src="./uploads/<?=$FOLDER?>/<?=$NIK?>/<?=$FILE?>" width="100%"/></p></br>
                    </div>
                </div>
                 
            </div>
        </div>
        <div class="pull-right">
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>