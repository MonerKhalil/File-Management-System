<?php

namespace App\Http\Controllers;

use App\HelperClasses\MyApp;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class NotificationController extends Controller
{
    /**
     * @param Request $request
     * @return Response|RedirectResponse|null
     * @author moner khalil
     */
    public function AllNotificationsPrintTable(Request $request): Response|RedirectResponse|null
    {
        $dataFilter = $request->filter;
        $user = MyApp::Classes()->getUser();
        $queryAudit = $user->notifications()->where("data->type","print");
        $notifications = $this->MainProcessNotification($dataFilter,$queryAudit);
        return $this->responseSuccess("pages.notification.print.print",compact("notifications"));
    }

    /**
     * @param Request $request
     * @return Response|RedirectResponse|null
     * @author moner khalil
     */
    public function AllNotificationsAuditUserShow(Request $request): Response|RedirectResponse|null
    {
        $dataFilter = $request->filter;
        $user = MyApp::Classes()->getUser();
        $queryAudit = $user->notifications()->where("data->type","audit");
        $notifications = $this->MainProcessNotification($dataFilter,$queryAudit);
        return $this->responseSuccess("pages.notification.audit.audit",compact("notifications"));
    }

    public function getAllNotificationsWithOutAudit(Request $request){
        $notifications = $this->getNotificationsMain($request,false);
        return $this->responseSuccess("pages.notification.basic.index", compact("notifications"));
    }
    public function getNotifications(Request $request){
        $notifications = $this->getNotificationsMain($request,true);
        return $this->responseSuccess(null, compact("notifications"));
    }

    public function getNotificationsMain($request,$withAudit=true){
        $user = MyApp::Classes()->getUser();
        $dataFilter = $request->filter;
        $data = match ($request->input("status")) {
            "Read" => $user->readNotifications(),
            "unRead" => $user->unreadNotifications(),
            default => $user->notifications(),
        };
        if (!$withAudit){
            $data = $data->whereNot("data->type","audit")->whereNot("data->type","print");
        }
        $data = $data->orderBy("created_at","desc");
        $data = (isset($dataFilter['start_date']) && !is_null($dataFilter['start_date']) && isset($dataFilter['end_date']) && !is_null($dataFilter['end_date']))
            ? $data->whereBetween('created_at',[$dataFilter['start_date'],$dataFilter['end_date']]) : $data;
        return MyApp::Classes()->Search->dataPaginate($data);

    }

    public function clearNotifications(){
         MyApp::Classes()->getUser()->notifications()->delete();
        return $this->responseSuccess(null,null,"delete",MyApp::RouteHome);
    }

    /**
     * @description show file
     * @return string
     */
    public function errorLog(): string
    {
        return "...";
    }

    /*
    * @descriptions : Ajax Request -> Work Update Notification To Read
    */
    public function editNotificationsToRead()
    {
        MyApp::Classes()->getUser()->unreadNotifications()->update([
            "read_at"=>Carbon::now()
        ]);
        return response()->json(["message"=>"Success Read Notification"]);
    }

    /*
    * @descriptions : Ajax Request -> Work Update Notification To Read
    */
    public function removeNotification(Request $request)
    {
        $notify = MyApp::Classes()->getUser()->notifications()->where("id",$request->id_notify)->first();
        if (!is_null($notify)){
            $notify->delete();
            return response()->json(["message"=>"Success Read Notification"]);
        }
        return response()->json(["error"=>"dont exists id Notification"]);
    }

    private function MainProcessNotification($dataFilter,$query){
        if (is_array($dataFilter)){
            $idsNotificationsFilter = $query->get()->map(function ($item) {
                return $this->resolveDataItem($item);
            });
            $idsNotificationsFilter = $idsNotificationsFilter->filter(function ($item) use ($dataFilter) {
                $check = true;
                if (isset($dataFilter['user_name'])) {
                    $check = str_contains($item->data['data']['user_name'], $dataFilter['user_name']);
                }
                if (isset($dataFilter['table_name'])) {
                    $check = $check && str_contains($item->data['data']['table_name'], $dataFilter['table_name']);
                }
                if (isset($dataFilter['event'])) {
                    $check = $check && str_contains($item->data['data']['event'], $dataFilter['event']);
                }
                if (isset($dataFilter['date'])) {
                    $check = $check && str_contains($item->data['data']['date'], $dataFilter['date']);
                }
                return $check;
            })->pluck('id');
            $data = $query->whereIn("id", $idsNotificationsFilter);
            $data = (isset($dataFilter['start_date']) && !is_null($dataFilter['start_date']) && isset($dataFilter['end_date']) && !is_null($dataFilter['end_date']))
                ? $data->whereBetween('created_at',[$dataFilter['start_date'],$dataFilter['end_date']]) : $data;
        }else{
            $data = $query;
        }
        $data = $data->orderBy("created_at","desc");
        $data = MyApp::Classes()->Search->dataPaginate($data);
        if ($data instanceof LengthAwarePaginator){
            $data = $data->through(function ($item) {
                return $this->resolveDataItem($item);
            });
        }else{
            $data = $data->map(function ($item) {
                return $this->resolveDataItem($item);
            });
        }
        return $data;
    }

    private function resolveDataItem($item){
        $data = $item->data;
        $data['data']['event'] = __("messages.".$item->data['data']['event']);
        $data['data']['table_name'] = __("messages.".$item->data['data']['table_name']);
        $data['data']['date'] = \Carbon\Carbon::parse($item->created_at)->diffForHumans();
        $item->data = $data;
        return $item;
    }
}
