<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimelinesCreateRequest;
use App\Http\Requests\TimelinesUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;
use App\Http\Resources\TimelinesResource;
use App\Models\Timeline;

class TimelinesController extends Controller
{
    public function create(TimelinesCreateRequest $request): JsonResponse
    {
        try {
            $timeline = Timeline::create($request->all());

            $timeline->load('user');
            $timeline->load('project');

            return Response::handler(
                201,
                'Berhasil membuat timeline',
                TimelinesResource::make($timeline)
            );
        }catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal membuat timeline',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function getAll(Request $request): JsonResponse
    {
        try {
            $timelines = Timeline::with(['user', 'project'])
                ->whereHas('project')
                ->join('tp_1_projects', 'tp_6_timelines.project_id', '=', 'tp_1_projects.id')
                ->orderBy('tp_1_projects.name', 'asc')
                ->select('tp_6_timelines.*')
                ->paginate($request->query('limit', 10));

            if ($timelines->isEmpty()) {
                return Response::handler(
                    200,
                    'Berhasil mengambil data timeline'
                );
            }

            return Response::handler(
                200,
                'Berhasil mengambil data timeline',
                TimelinesResource::collection($timelines),
                Response::pagination($timelines)
            );
        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal mengambil data timeline',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $query = Timeline::with(['user', 'project']);

            // if ($request->has('title')) {
            //     $query->where('title', 'like', '%' . $request->query('title') . '%');
            // }

            if ($request->has('project_id')) {
                $query->where('project_id', $request->query('project_id'));
            }

            if ($request->has('user_id')) {
                $query->where('user_id', $request->query('user_id'));
            }

            $timelines = $query->paginate($request->query('limit', 10));

            if ($timelines->isEmpty()) {
                return Response::handler(
                    200,
                    'Berhasil mengambil data timeline'
                );
            }

            return Response::handler(
                200,
                'Berhasil mengambil data timeline',
                TimelinesResource::collection($timelines),
                Response::pagination($timelines)
            );
        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal mengambil data timeline',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function update(TimelinesUpdateRequest $request, $id): JsonResponse
    {
        try {
            dd($request->all());
            $timeline = Timeline::find($id);

            if (!$timeline) {
                return Response::handler(
                    400,
                    'Gagal mengubah data timeline',
                    [],
                    [],
                    'Data timeline tidak ditemukan.'
                );
            }

            $timeline->update($request->all());

            return Response::handler(
                200,
                'Berhasil mengubah data timeline',
                TimelinesResource::make($timeline)
            );
        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal mengubah data timeline',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function softDelete($id): JsonResponse
    {
        try {
            $timeline = Timeline::find($id);

            if (!$timeline) {
                return Response::handler(
                    400,
                    'Gagal menghapus data timeline',
                    [],
                    [],
                    'Data timeline tidak ditemukan.'
                );
            }

            $timeline->delete();

            return Response::handler(
                200,
                'Berhasil menghapus data timeline'
            );
        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal menghapus data timeline',
                [],
                [],
                $e->getMessage()
            );
        }
    }
}
