<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Http\Requests\SpektekBulkCreateRequest;
use App\Http\Requests\SpektekCreateRequest;
use App\Http\Requests\SpektekUpdateRequest;
use App\Http\Resources\SpektekResource;
use App\Http\Resources\SpektekResourceWithSub;
use App\Models\Spektek;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpektekController extends Controller
{
    public function create(SpektekCreateRequest $request): JsonResponse
    {
        try {
            $spektek = Spektek::where('name', $request->name)
                ->where('project_id', $request->project_id)
                ->exists();

            if ($spektek) {
                return Response::handler(
                    400,
                    'Gagal membuat Spektek',
                    [],
                    [],
                    ['name' => ['Nama spektek sudah ada.']]
                );
            }

            $request->merge([
                'qty_nominal' => $request->qty_total != 0
                    ? $request->total_nominal / $request->qty_total
                    : 0,
                'progress_percentage' => $request->progress_percentage ?? 0,
            ]);

            $spektek = Spektek::create($request->all());

            return Response::handler(
                201,
                'Spektek berhasil dibuat',
                SpektekResource::make($spektek)
            );

        }catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal membuat Spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function bulkCreate(SpektekBulkCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            if(empty($request->validated())) {
                return Response::handler(
                    400,
                    'Gagal membuat Spektek',
                    [],
                    [],
                    ['data' => ['Data spektek tidak boleh kosong.']]
                );
            }

            $spekteks = [];

            foreach ($request->validated() as $data) {

                $exists = Spektek::where('name', $data['name'])
                    ->where('project_id', $data['project_id'])
                    ->exists();

                if ($exists) {
                    DB::rollBack();

                    return Response::handler(
                        400,
                        'Gagal membuat Spektek',
                        [],
                        [],
                        ['name' => ["Nama spektek '{$data['name']}' sudah ada."]]
                    );
                }

                $data['qty_nominal'] = $data['qty_total'] != 0
                    ? $data['total_nominal'] / $data['qty_total']
                    : 0;

                $data['progress_percentage'] = $data['progress_percentage'] ?? 0;

                $spekteks[] = Spektek::create($data);
            }

            DB::commit();

            return Response::handler(
                201,
                'Spektek berhasil dibuat',
                SpektekResource::collection(collect($spekteks))
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return Response::handler(
                500,
                'Gagal membuat Spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function getAll(): JsonResponse
    {
        try {
            $spekteks = Spektek::with('project')->get();

            return Response::handler(
                200,
                'Berhasil mendapatkan semua spektek',
                SpektekResource::collection($spekteks)
            );
        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal mendapatkan semua spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function getById($id): JsonResponse
    {
        try {
            $spekteks = Spektek::with('project')->where('id', $id)->first();

            if (!$spekteks) {
                return Response::handler(
                    404,
                    'Spektek tidak ditemukan',
                    [],
                    [],
                    ['id' => ['Spektek tidak ditemukan.']]
                );
            }

            return Response::handler(
                200,
                'Berhasil mendapatkan spektek',
                SpektekResource::make($spekteks)
            );
        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal mendapatkan spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $query = Spektek::query();

            foreach ($request->all() as $key => $value) {
                if (in_array($key, ['id','name', 'type', 'project_id'])) {
                    $query->where($key, 'LIKE', "%{$value}%");
                }
            }

            $spekteks = $query->with('project')->get();

            if($request->has('with_sub') == true) {
                $spekteks = SpektekResourceWithSub::collection($spekteks);
            }else {
                $spekteks = SpektekResource::collection($spekteks);
            }

            return Response::handler(
                200,
                'Berhasil mencari spektek',
                $spekteks
            );
        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal mencari spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function update($id, SpektekUpdateRequest $request): JsonResponse
    {
        try {
            $spektek = Spektek::find($id);

            if (!$spektek) {
                return Response::handler(
                    404,
                    'Spektek tidak ditemukan',
                    [],
                    [],
                    ['id' => ['Spektek tidak ditemukan.']]
                );
            }

            $request->only([
                'name',
                'type',
                'qty_total',
                'qty_nominal',
                'total_nominal',
                'detail',
                'note',
                'project_id'
            ]);

            $nameValidate = Spektek::where('name', $request->name)
                ->where('project_id', $request->project_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($nameValidate) {
                return Response::handler(
                    400,
                    'Gagal mengupdate spektek',
                    [],
                    [],
                    ['name' => ['Nama spektek sudah ada.']]
                );
            }

            $qtyTotal = $request->has('qty_total')
                ? $request->qty_total
                : $spektek->qty_total;

            $totalNominal = $request->has('total_nominal')
                ? $request->total_nominal
                : $spektek->total_nominal;

            if ($qtyTotal !== null && $totalNominal !== null) {
                $request->merge([
                    'qty_nominal' => $qtyTotal != 0
                        ? $totalNominal / $qtyTotal
                        : 0
                ]);
            }

            $spektek->update($request->all());

            return Response::handler(
                200,
                'Spektek berhasil diupdate',
                SpektekResource::make($spektek)
            );

        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal mengupdate spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function updateQtyReceived($id, SpektekUpdateRequest $request): JsonResponse
    {
        try {
            $spektek = Spektek::find($id);

            if (!$spektek) {
                return Response::handler(
                    404,
                    'Spektek tidak ditemukan',
                    [],
                    [],
                    ['id' => ['Spektek tidak ditemukan.']]
                );
            }

            $data = [
                'qty_received' => $request->qty_received ?? 0,
                'qty_updated_at' => now(),
            ];

            $spektek->update($data);

            return Response::handler(
                200,
                'Jumlah diterima berhasil diupdate',
                SpektekResource::make($spektek)
            );

        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal mengupdate jumlah diterima spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function updateProgressPercentage($id, SpektekUpdateRequest $request): JsonResponse
    {
        try {
            $spektek = Spektek::find($id);

            if (!$spektek) {
                return Response::handler(
                    404,
                    'Spektek tidak ditemukan',
                    [],
                    [],
                    ['id' => ['Spektek tidak ditemukan.']]
                );
            }

            $data = [
                'progress_percentage' => $request->progress_percentage ?? 0,
                'progress_updated_at' => now(),
            ];

            $spektek->update($data);

            return Response::handler(
                200,
                'Persentase kemajuan berhasil diupdate',
                SpektekResource::make($spektek)
            );

        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal mengupdate persentase kemajuan spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function softDelete($id): JsonResponse
    {
        try {
            $spektek = Spektek::findOrFail($id);

            if (!$spektek) {
                return Response::handler(
                    404,
                    'Spektek tidak ditemukan',
                );
            }

            $spektek->delete();

            return Response::handler(
                200,
                'Berhasil menghapus spektek',
            );
        } catch (ModelNotFoundException $e) {
            return Response::handler(
                404,
                'Spektek tidak ditemukan'
            );

        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal menghapus spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }
}
