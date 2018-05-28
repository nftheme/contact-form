<?php
namespace Vicoders\ContactForm\Services;

use League\Csv\CannotInsertRecord;
use League\Csv\Reader;
use League\Csv\Writer;
use League\Flysystem\Exception;
use NF\Facades\App;
use NF\Facades\Storage;

/**
 * Export records to csv or exel file
 */
class Office
{
    protected $_filename = 'name_default';
    /**
     * [exportFile description]
     * @param  [type] $name       name of file when export
     * @param  [type] $data       an array data to insert to csv file
     * @param  [type] $fileExtension [type export file]
     * @return [type]                [description]
     */
    public function export($name, $data, $fileExtension = 'csv')
    {
        $year       = current_time('Y');
        $month      = current_time('m');
        $day        = current_time('d');
        $h_i_s_time = current_time('his');

        $this->_filename = snake_case(str_slug($name)) . "_{$year}{$month}{$day}_{$h_i_s_time}.{$fileExtension}";
        $data = $this->parseDataExport($data);

        switch ($fileExtension) {
            case 'csv':
                $path = $this->_exportCSV($data);
                break;
            case 'xlsx':
                $path = $this->_exportXLSX($data);
                break;
            default:
                $path = $this->_exportCSV($data);
                break;
        }
        return $path;
    }

    /**
     * [insertHeaderTitle For export function]
     * @param  \League\Csv\Writer $csv [description]
     * @return \League\Csv\Writer [description]
     */
    public function insertHeaderTitle($csv, $headtitles = [])
    {
        if (!empty($headtitles)) {
        	$title_export_file[] = '#';
            foreach ($headtitles as $key => $field) {
                $title_export_file[] = $field;
            }
        	$csv->insertOne($title_export_file);
        }
        return $csv;
    }

    public function parseDataExport($data) {
        $tmp_data = [];
        if(empty($data)) {
            throw new Exception("Data not found", 404);
        }
        foreach ($data as $key => $item) {
            $tmp_data[] = json_decode($item->data, true);
        }

        return $tmp_data;
    }

    private function _exportCSV($exports)
    {
        if (empty($exports)) {
            throw new BadRequestHttpException("Data empty", null, 1);
        }
        foreach ($exports as $key => $export) {
            $exports[$key] = (array) $export;
        }
        $year        = current_time('Y');
        $month       = current_time('m');
        $path_upload = wp_upload_dir($year . '/' . $month);
        $path_export = $path_upload['path'] . '/export';
        if (!is_dir($path_export)) {
            mkdir($path_export, 0755);
        }
        $link_file = site_url('wp-content/uploads/' . $year . '/' . $month . '/export/' . ($this->_filename));
        $path_file = $path_export . "/{$this->_filename}";
        $success   = Storage::write($file_path, '');

        $file = fopen($path_file, "w");
        fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        foreach ($exports as $key => $export) {
            $exports[$key] = (array) $export;
        }
        if (empty($exports)) {
            throw new BadRequestHttpException("Data empty", null, 1);
        }
        fputcsv($file, array_keys($exports[0]), $delimiter = ',', $enclosure = '"');
        foreach ($exports as $export) {
            fputcsv($file, array_map(function ($value) {
                return trim($value);
            }, $export), $delimiter = ',', $enclosure = '"');
        }

        fclose($file);

        $url = $path_file;
        return $link_file;
    }

    public function _exportXLSX($exports) {
        
    }
}
