<?php
function load_data_xl($instansi) {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();
        $response = $this->Mt_pn_upload_xls_temp->get_ver_pnwl_tambahan_two($instansi, $currentpage, $keyword, $rowperpage, true);
        $responseCount = $this->Mt_pn_upload_xls_temp->get_ver_pnwl_tambahan_two($instansi, $currentpage, $keyword, $rowperpage, false);
        
        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($responseCount->num_rows()),
            "iTotalDisplayRecords" => intval($responseCount->num_rows()),
            "aaData" => $response->result()
        );
        
        $this->to_json($dtable_output);
    }
	?>