<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Telusur
{
    private $CI;
    private $config_telusur;
    private $jenis_harta;
    private $nik;
    private $hubungan;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('mongo_db');
        $this->config_telusur = $this->CI->config->item('koneksi_telusur');
    }

    function set_jenis_harta($jenis_harta)
    {
        $this->jenis_harta = $jenis_harta;
        return $this;
    }

    function set_nik($nik)
    {
        $this->nik = $nik;
        return $this;
    }

    function set_hubungan($hubungan)
    {
        $this->hubungan = $hubungan;
        return $this;
    }

    /**
     *  Returns data telusur from telusur database.
     *
     *  @return    array
     */
    function get(): array
    {
        $result = [];

        if (!empty($this->nik)) {
            $collections = $this->collections();
    
            foreach ($collections as $collection) {
                if (isset($this->config_telusur["collections"][$collection["name"]])) {
                    $project_pipeline = $this->project_pipeline($collection["name"]);
        
                    $data = $this->CI->mongo_db->aggregate(
                        $collection["name"],
                        [
                            [
                                '$match' => ['nik' => $this->nik]
                            ],
                            [
                                '$project' => $project_pipeline
                            ],
                            // [
                            //     '$limit' => 10
                            // ]
                        ],
                        [
                            'allowDiskUse' => TRUE,
                            'cursor' => [
                                'batchSize' => 0
                            ]
                        ]
                    );
        
                    $data = array_map(function($data) use ($collection) {
                        return $this->data_mapping($data, $collection["name"]);
                    }, $data);
        
                    $result = array_merge($result, $data);
                }
            }
        }

        return $result;
    }

    /**
     *  Returns construction for operator $gte.
     *
     *  @param     array   $data  Data to mapping
     *  @param     string  $collection_name  Collection name
     *  @return    array
     */
    function data_mapping($data, $collection_name): array
    {
        $datatable_columns = $this->datatable_columns();
        $fields = $this->config_telusur["collections"][$collection_name]["fields"];

        $result = [];

        if (!empty($this->hubungan)) {
            $result['hubungan'] = $this->hubungan;
        }

        foreach ($datatable_columns as $datatable_column) {
            $value = null;

            if (isset($fields[$datatable_column])) {
                if (is_array($fields[$datatable_column])) {
                    if (isset($data[$datatable_column])) {
                        $value = $data[$datatable_column];
                    }
                } else {
                    if (isset($data[$fields[$datatable_column]])) {
                        $value = $data[$fields[$datatable_column]];
                    }
                }
            }

            $result[$datatable_column] = $value;
        }

        return $result;
    }

    /**
     *  Returns project pipeline parameter.
     *
     *  @param     string  $collection_name  Collection name
     *  @return    array
     */
    function project_pipeline($collection_name): array
    {
        $result = [];

        $fields = $this->config_telusur["collections"][$collection_name]["fields"];

        foreach ($fields as $key => $field) {
            if (is_array($field)) {
                $result[$key] = $field;
            } else {
                $result[$field] = 1;
            }
        }

        return $result;
    }

    /**
     *  Returns list collections from datatbase.
     *
     *  @return    array
     */
    function collections(): array
    {
        $result = [];

        if (!empty($this->jenis_harta)) {
            $type = $this->jenis_harta == "htb" ? "htb" : "kendaraan";
            
            $collections = $this->CI->mongo_db->command(['listCollections' => TRUE]);
    
            $result = array_filter($collections, function($collection) use ($type) {
                return $this->collection_type($collection['name']) == $type;
            });
        }

        return $result;
    }

    /**
     *  Returns collection type from collection name.
     *
     *  @param     string  $collection_name  Name of collection
     *  @return    string
     */
    function collection_type($collection_name): string
    {
        return explode("_", $collection_name)[0];
    }

    /**
     *  Returns datatable columns from koneksi telusur config by jenis harta: htb or hb.
     *
     *  @return    array
     */
    function datatable_columns(): array
    {
        $result = [];

        if (!empty($this->jenis_harta)) {
            if (isset($this->config_telusur["datatable_columns_".$this->jenis_harta])) {
                $result = $this->config_telusur["datatable_columns_".$this->jenis_harta];
            }
        }

        return $result;
    }
}
