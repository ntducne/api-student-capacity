<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QA\QaStore;
use App\Models\QuestionAndAnswer;
use App\Services\Traits\TResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QAController extends Controller
{
    use TResponse;

    protected QuestionAndAnswer $questionAndAnswer;
    public function __construct(QuestionAndAnswer $questionAndAnswer)
    {
        $this->questionAndAnswer = $questionAndAnswer;
    }

    function dataQA(){
        try {
            $data = QuestionAndAnswer::sort(request('sort') == 'asc' ? 'asc' : 'desc', request('sort_by') ?? null, 'question_and_answers')
                ->withCount(['created_by', 'updated_by', 'deleted_by'])
                ->search(request('q') ?? null, ['category_id', 'question', 'answer']);
            return $data;
        } catch (\Throwable $th) {
            Log::error('Error Data QA: ' . $th->getMessage());
            return false;
        }
    }


    public function internship()
    {
        try {
            $data = $this->dataQA();
            $data = $data->where('category_id', 1)->where('status', 1)->get();
            return $this->responseApi(true, $data);
        } catch (\Throwable $th) {
            Log::error('Error get data internship: '. $th->getMessage());
            return $this->responseApi(false);
        }
    }

    public function job()
    {
        try {
            $data = $this->dataQA();
            $data = $data->where('category_id', 2)->where('status', 1)->get();
            return $this->responseApi(true, $data);
        } catch (\Throwable $th) {
            Log::error('Error get data job:'. $th->getMessage());
            return $this->responseApi(false);
        }
    }

    public function event()
    {
        try {
            $data = $this->dataQA();
            $data = $data->where('category_id', 3)->where('status', 1)->get();
            return $this->responseApi(true, $data);
        } catch (\Throwable $th) {
            Log::error('Error get data event: '. $th->getMessage());
            return $this->responseApi(false);
        }
    }

    public function index(Request $request){
        $data = $this->dataQA()->paginate($request->limit ?? 10);
        return view('pages.question-answer.index', compact('data'));
    }

    public function create(){
        return view('pages.question-answer.create');
    }

    public function store(QaStore $request){
        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();
            $this->questionAndAnswer->create($data);
            return redirect(route('admin.qa.index'))->with('success','Thêm mới thành công');
            // return $this->responseApi(true, $data);
        } catch (\Throwable $th) {
            Log::error('Error create QA: '. $th->getMessage());
            return redirect(route('admin.qa.index'))->with('error', 'Có lỗi xảy ra');
        }
    }

    public function edit($id){
        $item = $this->questionAndAnswer->find($id);
        return view('pages.question-answer.edit', compact('item'));
    }

    public function update(Request $request, $id){
        try {
            $data = $request->except('_token', '_method');
            $data['updated_by'] = auth()->id();
            $this->questionAndAnswer->where('id', $id)->update($data);
            return redirect(route('admin.qa.index'))->with('success', 'Cập nhật thành công');
        } catch (\Throwable $th) {
            Log::error('Error update QA: '. $th->getMessage());
            return redirect(route('admin.qa.index'))->with('error', 'Có lỗi xảy ra');
        }
    }

    public function destroy($id){
        try {
            $this->questionAndAnswer->where('id', $id)->update(['deleted_by' => auth()->id()]);
            $this->questionAndAnswer->find($id)->delete();
            return redirect(route('admin.qa.index'))->with('success', 'Xóa thành công');
        } catch (\Throwable $th) {
            Log::error('Error delete QA: '. $th->getMessage());
            return redirect(route('admin.qa.index'))->with('error', 'Có lỗi xảy ra');
        }
    }

    public function trash(Request $request){
        $data = $this->dataQA()->onlyTrashed()->paginate($request->limit ?? 10);
        return view('pages.question-answer.trash', compact('data'));
    }

    public function restore($id){
        try {
            $this->questionAndAnswer->where('id', $id)->update(['status' => 1, 'deleted_by' => null]);
            $this->questionAndAnswer->withTrashed()->find($id)->restore();
            return redirect(route('admin.qa.index'))->with('success', 'Khôi phục thành công');
        } catch (\Throwable $th) {
            Log::debug("Error Restore QA: " . $th->getMessage());
            return redirect(route('admin.qa.index'))->with('error', 'Có lỗi xảy ra');
        }
    }

    public function forceDelete($id){
        try {
            $this->questionAndAnswer->withTrashed()->find($id)->forceDelete();
            return redirect(route('admin.qa.trash'))->with('success', 'Xóa vĩnh viễn thành công');
        } catch (\Throwable $th) {
            Log::debug("Error Force Delete QA: " . $th->getMessage());
            return redirect(route('admin.qa.trash'))->with('error', 'Có lỗi xảy ra');
        }
    }
}