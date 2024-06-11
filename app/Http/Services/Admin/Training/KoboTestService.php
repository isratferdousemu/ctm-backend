<?php

namespace App\Services\Mne;

use App\Models\Mne\Kobo\KoboQuestionAnswer;
use App\Models\Mne\Kobo\KoboQuestionOption;
use App\Models\Mne\Kobo\KoboQuestions;
use App\Models\Mne\Kobo\KoboQuestionsSet;
use Illuminate\Support\Facades\Http;
use App\Models\KoboMemberInformation;
use Illuminate\Support\Facades\File;

/**
 * Class KoboDataService
 */
class KoboDataService
{

    private $kobo_url = '';
    private $kobo_token = '';

    public function __construct()
    {
        $this->kobo_url = env('KOBO_URL');
        $this->kobo_token = env('KOBO_TOKEN');
    }

    public function loadKoboData($url, $is_suffix = true)
    {
        $kobo_url = $is_suffix ? ($this->kobo_url . $url) : $url;
        $kobo_token = 'Token ' . $this->kobo_token;
        $response = Http::acceptJson()
            ->withHeaders([
                'Authorization' => $kobo_token
            ])
            ->get($kobo_url);
        $successful = $response->successful();
        if ($successful) {
            return [
                'status' => true,
                'msg' => 'Data fetch successfully',
                'result' => $response->json(),
            ];
        }
        $failed = $response->failed();
        if ($failed) {
            return [
                'status' => false,
                'msg' => 'Data fetch failed',
                'result' => []
            ];
        }
        $clientError = $response->clientError();
        if ($clientError) {
            return [
                'status' => false,
                'msg' => 'Kobo credentials does not match ',
                'result' => []
            ];
        }
        $serverError = $response->serverError();
        if ($serverError) {
            return [
                'status' => false,
                'msg' => 'Kobo server error found ',
                'result' => []
            ];
        }
        return [
            'status' => false,
            'msg' => 'Unknown error',
            'result' => []
        ];
    }


    public function getQuestionSets()
    {
        $setsData = $this->loadKoboData('api/v2/assets?format=json');
        $results = $setsData['result']['results'] ?? [];

        if (is_array($results)) {
            foreach ($results as $result) {
                $this->saveKoboQuestionSet($result);
            }
        }
        return $setsData;
    }

    public function saveKoboQuestionSet(array $result)
    {
        $uid = $result['uid'] ?? null;
        $submission_count = $result['deployment__submission_count'] ?? null;
        $url = $result['url'] ?? null;
        $item = KoboQuestionsSet::where('uid', $uid)->first();
        if (!$item) {
            $item = new  KoboQuestionsSet();
            $item->uid = $uid;
            $item->url = $url;
            $item->data_url = $result['data'] ?? null;
            $item->date_created = $result['date_created'] ?? null;
        }
        $item->name = $result['name'] ?? null;
        $item->deployment__submission_count = $submission_count;
        $item->date_modified = $result['date_modified'] ?? null;
        $item->has_deployment = $result['has_deployment'] ?? null;
        $item->deployment_active = $result['deployment_active'] ?? null;
        $item->save();
    }


    public function saveQuestions()
    {
        $questionSets =  KoboQuestionsSet::with('questions')->where('deployment__active', 1)->get();
        // $questionSets =  KoboQuestionsSet::with('questions')->where('id', 3)->where('deployment__active', 1)->get();
        $notInType = ["start", "end", "begin_group", "end_group", "note", "calculate", "geoshape"];
        $notInName = ['_version_'];
        $setsData = '';
        foreach ($questionSets as $q_set) {
            $set_id = $q_set->id;
            $setsData = $this->loadKoboData($q_set->url, false);
            $result = $setsData['result'] ?? [];
            $survey = $result['content']['survey'] ?? [];
            $choices = $result['content']['choices'] ?? [];
            foreach ($survey as $question) {
                $kUid = $question['$kuid'] ?? null;
                $label = $question['label'][0] ?? null;
                $autoName = $question['$autoname'] ?? null;
                $select_from_list_name = $question['select_from_list_name'] ?? null;
                $type = $question['type'] ?? null;
                if (!in_array($type, $notInType) && !in_array($autoName, $notInName)) {
                    $question = KoboQuestions::where('questions_set_id', $set_id)
                        ->where('auto_name', $autoName)
                        ->first();
                    if (!$question) {
                        $question = new KoboQuestions();
                        $question->uid = $kUid;
                        $question->select_from_list_name = $select_from_list_name;
                        $question->questions_set_id = $set_id;
                    }
                    $question->name = $label ? $label : $autoName;
                    $question->auto_name = $autoName;
                    $question->save();
                    $this->saveQuestionOptions($choices, $select_from_list_name, $question->id);
                }
            }
        }
        return $setsData;
    }

    public function saveQuestionOptions($choices, $list_name, $question_id)
    {
        foreach ($choices as $option) {
            $question_list_name = $option['list_name'] ?? null;
            if ($list_name == $question_list_name) {
                $kUid = $option['$kuid'] ?? null;
                $label = $option['label'][0] ?? null;
                $autoValue = $option['$autovalue'] ?? null;
                $name = $option['name'] ?? null;
                $question = KoboQuestionOption::where('questions_id', $question_id)->where('uid', $kUid)->first();
                if (!$question) {
                    $question = new KoboQuestionOption();
                    $question->uid = $kUid;
                    $question->questions_id = $question_id;
                }
                $question->list_name = $question_list_name;
                $question->label = $label;
                $question->name = $name;
                $question->auto_value = $autoValue;
                $question->save();
            }
        }
    }


    public function updateQuestionAnswer()
    {

        $questionSets =  KoboQuestionsSet::with('questions')->where('deployment__active', 1)->get();
        // $questionSets =  KoboQuestionsSet::with('questions')->where('id', 3)->where('deployment__active', 1)->get();
        $answers = '';
        foreach ($questionSets as $set) {
            $data_url = $set->data_url;
            $questions_set_id = $set->id;
            if ($data_url) {
                $questions = $set->questions;
                $answers = $this->loadKoboData($set->data_url, false);
                $resData = $answers['result']['results'] ?? [];
                foreach ($resData as $key => $answer) {
                    // dump($answer);
                    // insert kobo_member_information
                    // download_large_url ,download_medium_url , download_small_url , download_url , filename
                    // return $answer['_attachments'][0]['download_large_url'];
                    // return "https://www.istockphoto.com/photo/group-of-business-people-standing-in-hall-smiling-and-talking-together-gm530685719-530685719?utm_source=pixabay&utm_medium=affiliate&utm_campaign=SRP_image_sponsored&utm_content=https%3A%2F%2Fpixabay.com%2Fimages%2Fsearch%2Flink%2F&utm_term=link";
                    // $file =  file_get_contents("https://www.istockphoto.com/photo/group-of-business-people-standing-in-hall-smiling-and-talking-together-gm530685719-530685719?utm_source=pixabay&utm_medium=affiliate&utm_campaign=SRP_image_sponsored&utm_content=https%3A%2F%2Fpixabay.com%2Fimages%2Fsearch%2Flink%2F&utm_term=link");

                    // return $answer['_attachments'][0]['download_large_url'];

                    // $answer['_attachments'][0]['filename'];



                    // data[0].download_large_url
                    // $file =  file_get_contents($answer['_attachments'][0]['download_large_url']);
                    // $name = substr($file, strrpos($file, '/') + 1);
                    // return $name;
                    // $file = file_get_contents($answer['_attachments'][0]['download_medium_url']);
                    // $file = file_get_contents($answer['_attachments'][0]['download_small_url']);
                    // $file = file_get_contents($answer['_attachments'][0]['download_url']);
                    // $file = file_get_contents($answer['_attachments'][0]['filename']);
                    // return $file;

                    if ($answer) {
                        $file = null;
                        if (isset($answer['_attachments'][0]['download_medium_url'])) {
                            $file = $answer['_attachments'][0]['download_medium_url'];
                        }

                        $nid = $answer['group_uc8rz35/NID_BRN_Number_of_interviewer'] ?? null;
                        if ($nid) {
                            $phone = $answer['group_uc8rz35/_1_12_Cell_phone_of_interviewer'] ?? null;
                            if ($answer['_geolocation']) {
                                $latitude = $answer['_geolocation'][0] ?? null;
                                $longitude = $answer['_geolocation'][1] ?? null;
                            }
                            KoboMemberInformation::updateOrCreate(
                                ['nid' => $nid],
                                ['nid' => $nid, 'phone' => $phone, 'latitude' => $latitude, 'longitude' => $longitude, 'image' =>  $file]
                            );
                        }
                    }

                    // return $answer['_attachments'];
                    // return $answer['group_uc8rz35/_1_12_Cell_phone_of_interviewer'];
                    // return $answer['_geolocation'];
                    // return $answer['_geolocation'][0];
                    // return $answer['group_uc8rz35/NID_BRN_Number_of_interviewer'];

                    $_id = $answer['_id'] ?? null;
                    $start = $answer['start'] ?? null;
                    $end = $answer['end'] ?? null;
                    $submission_time = $answer['_submission_time'] ?? null;
                    foreach ($answer as $key => $value) {

                        $group_split = explode('/', $key);
                        $q_auto_name = end($group_split);
                        $question = $questions->where('auto_name', $q_auto_name)->first();
                        if ($question) {
                            $ansItem = KoboQuestionAnswer::where('questions_set_id', $questions_set_id)
                                ->where('questions_id', $question->id)->where('row_id', $_id)->first();
                            if (!$ansItem) {
                                $ansItem = new KoboQuestionAnswer();
                                $ansItem->questions_set_id = $questions_set_id;
                                $ansItem->questions_id = $question->id;
                                $ansItem->row_id = $_id;
                                $ansItem->answer = $value;
                                $ansItem->submission_time = $submission_time;
                                $ansItem->start = $start;
                                $ansItem->end = $end;
                                $ansItem->save();
                            }
                        }
                    }
                }
            }
        }
        return $answers;
    }
}
