<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Models\QRCode;
use Illuminate\Http\File;

/**
 * Q R Codes Back-end Controller
 */
class QRCodes extends Controller
{

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();


        $this->prepareVars();

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'qrcodes');
    }

    public function QRCodeDownloader($id = null)
    {
        if (is_null($id)) {
            return \Response::redirectTo($this->actionUrl('index'));
        }
        $qrCode = $this->getQRCodeData($id);

        $this->vars['qrCode'] = $qrCode;
    }


    public function onDownloadQRCode($id)
    {
        $data = request()->all();
        $validator = \Validator::make($data, [
            'dimension' => [
                'required', 'min:150', 'numeric'
            ],
            'format' => [
                'required', 'in:SVG,PNG,EPS'
            ]
        ]);

        if ($validator->fails()) {
            $this->vars['errors'] = $validator->errors();
        } else {
            $qrCode = QRCode::find($id);
            if (!is_null($qrCode)) {
                $extension = strtolower($data['format']);
                $image = $qrCode->makeQRCode($data['dimension'], $extension);

                $outputFileName = md5($image).".$extension";
                $outputFileLocation = "temp/qrcodes/$outputFileName";
                \Storage::disk('local')->put($outputFileLocation, $image);

                return \Response::download(storage_path('app/' . $outputFileLocation))->deleteFileAfterSend(true);
            }
        }
    }

    private function getQRCodeData($id)
    {
        return QRCode::find($id);
    }
    public function prepareVars()
    {
        $this->vars['QRCodeDownloadUrl'] = null;
        if ($this->action === "update" || $this->action === "preview") {
            $this->vars['QRCodeDownloadUrl'] = $this->generateQRCodeDownloadURL($this->params[0]);
        }
    }

    private function generateQRCodeDownloadURL($id)
    {
        return $this->actionUrl('QRCodeDownloader', $id);
    }
}