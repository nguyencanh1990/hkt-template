<?php

namespace Helper;

use App\Models\Provider;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Common
{
    /**
     * @param UploadedFile $file
     * @param string $path
     * @param null $shopId
     * @return mixed
     */
    public static function uploadFile(UploadedFile $file, string $path = '', $userId = null)
    {
        $userId = $userId ?? Auth::id();
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($path, $fileName);
    }

    public static function uploadPhotoToS3($file, $path = 'photos'): string
    {
        // path can't not inclue character [/]
        return Storage::disk('s3')->put($path, $file, 'public');
    }

    public static function checkTokenFB($token): bool
    {
        try {
            $client = new \GuzzleHttp\Client();
            $url = 'https://graph.facebook.com/me?access_token=' . $token;
            $res = $client->request('GET', $url);
            if ($res->getStatusCode() == 200) {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * Generate common codes
     *
     * @param string $typeAbbre
     * @param string|null $agencyCode
     * @param string|null $lastestSerialCode
     *
     * @return string
     */
    public static function generateCommonCodes(string $typeAbbre, string $agencyCode = null, string $lastestSerialCode = null): string
    {

        // Get id type abbrevation and serial number
        $currentSerialNumber = self::getCurrentSerialNumber($typeAbbre, $lastestSerialCode);
        $currentSerialLetter = $lastestSerialCode ? Str::substr($lastestSerialCode, -1) : 'A';
        // Get next serial number and letter
        $nextSerialNumber = ++$currentSerialNumber;
        $nextSerialLetter = Str::upper($currentSerialLetter);

        if ($nextSerialNumber > 999) {
            $nextSerialNumber = 1;
            $nextSerialLetter = chr(ord($nextSerialLetter) + 1);
        }

        switch ($typeAbbre) {
            case config('common.list_category_abbrevation.schedule'):
                return sprintf(
                    "%s-%02d",
                    $typeAbbre,
                    $nextSerialNumber
                );
            case config('common.list_category_abbrevation.provider_transportation'):
                return sprintf(
                    "%s%03d%s",
                    $typeAbbre,
                    $nextSerialNumber,
                    $nextSerialLetter
                );
            default:
                return sprintf(
                    "%s%02d%s-%02d%03d%s",
                    $typeAbbre,
                    Str::substr(date('y'), -2),
                    $agencyCode,
                    date('m'),
                    $nextSerialNumber,
                    $nextSerialLetter
                );
        }
    }

    public function getCurrentSerialNumber($typeAbbre, $lastestSerialCode = null): string
    {
        if (!$lastestSerialCode) {
            return 0;
        }
        // remake input code
        try {
            switch ($typeAbbre) {
                case config('common.list_category_abbrevation.provider_transportation'):
                    if (!Provider::isValidCode($lastestSerialCode)) {
                        throw new Exception("Code format invalid!");
                    }
                    $lastestSerialCode = Str::replace($typeAbbre, '', $lastestSerialCode);
                    return number_format(Str::onlyNumbers($lastestSerialCode));
                case config('common.list_category_abbrevation.schedule'):
                    return number_format(Str::onlyNumbers($lastestSerialCode));
                default:
                    return number_format(Str::onlyNumbers(Str::substr($lastestSerialCode, 0, 3)));
            }
        } catch (Exception $exception) {
            return number_format(Str::onlyNumbers(Str::substr($lastestSerialCode, 0, 3)));
        }
    }

    /**
     * decode the JSON data
     * @param string $string
     * @return object
     */
    public static function json_validate(string $string): object
    {
        // decode the JSON data
        $result = json_decode($string);
        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }
        if ($error !== '') {
            // throw the Exception or exit // or whatever :)
            exit($error);
        }
        // everything is OK
        return $result;
    }

    /**
     * @desc 1 => 0001
     * @param int number
     * @param string|null $code
     * @return string
     */
    public static function leadingZeros(int $number, string $code = null): string
    {
        $str = str_pad($number, 4, '0', STR_PAD_LEFT);
        if (!empty($code)) {
            return $code . '-' . $str;
        }
        return $str;
    }

    /**
     * @param $datetime
     *
     * @return string
     */
    public static function formatToTime($datetime): string
    {
        return Carbon::parse($datetime)->format('H:i');
    }

    /**
     * @return string
     */
    public static function delPrefix(): string
    {
        $timestamp = Carbon::now()->getTimestamp();
        return "del_{$timestamp}_";
    }
}
