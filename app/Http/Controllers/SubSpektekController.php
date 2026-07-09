<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Http\Requests\SubSpektekCreateRequest;
use App\Http\Requests\SubSpektekUpdateRequest;
use App\Http\Resources\SubSpektekResource;
use App\Models\SubSpektek;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;



class SubSpektekController extends Controller
{
    public function create(SubSpektekCreateRequest $request): JsonResponse
    {
        try {
            $subSpektek = SubSpektek::where('name', $request->name)
                ->where('spektek_id', $request->spektek_id)
            ->exists();

            if ($subSpektek) {
                return Response::handler(
                    400,
                    'Gagal membuat Sub Spektek',
                    [],
                    [],
                    ['name' => ['Nama sub spektek sudah ada.']]
                );
            }

            $request->merge([
                'qty_nominal' => $request->qty_total != 0
                    ? $request->total_nominal / $request->qty_total
                    : 0
            ]);

            $subSpektek = SubSpektek::create($request->all());

            return Response::handler(
                201,
                'Sub Spektek berhasil dibuat',
                SubSpektekResource::make($subSpektek)
            );

        }catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal membuat Sub Spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }


    public function getAll(): JsonResponse
    {
        try {
            $subSpekteks = SubSpektek::with('spektek')->get();

            return Response::handler(
                200,
                'Berhasil mengambil data sub spektek',
                SubSpektekResource::collection($subSpekteks)
            );
        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal mengambil data sub spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function update(SubSpektekUpdateRequest $request, $id): JsonResponse
    {
        try {
            $subSpektek = SubSpektek::findOrFail($id);

            $request->only([
                'name',
                'qty_total',
                'qty_nominal',
                'total_nominal',
                'detail',
                'note',
                'spektek_id'
            ]);

            if($request->has('name')) {
                $nameValidate = SubSpektek::where('name', $request->name)
                    ->where('spektek_id', $request->spektek_id)
                    ->where('id', '!=', $id)
                    ->exists();
            }

            if($nameValidate) {
                return Response::handler(
                    400,
                    'Gagal memperbarui Sub Spektek',
                    [],
                    [],
                    ['name' => ['Nama sub spektek sudah ada.']]
                );
            }

            $qtyTotal = $request->has('qty_total')
                ? $request->qty_total
                : $subSpektek->qty_total;

            $totalNominal = $request->has('total_nominal')
                ? $request->total_nominal
                : $subSpektek->total_nominal;

            if ($qtyTotal !== null && $totalNominal !== null) {
                $request->merge([
                    'qty_nominal' => $qtyTotal != 0
                        ? $totalNominal / $qtyTotal
                        : 0
                ]);
            }

            $subSpektek->update($request->all());

            return Response::handler(
                200,
                'Sub Spektek berhasil diperbarui',
                SubSpektekResource::make($subSpektek)
            );
        } catch (ModelNotFoundException $e) {
            return Response::handler(
                404,
                'Sub Spektek tidak ditemukan'
            );
        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal memperbarui sub spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }

    public function softDelete($id): JsonResponse
    {
        try {
            $subSpektek = SubSpektek::findOrFail($id);
            $subSpektek->delete();

            return Response::handler(
                200,
                'Sub Spektek berhasil dihapus',
            );
        } catch (ModelNotFoundException $e) {
            return Response::handler(
                404,
                'Sub Spektek tidak ditemukan'
            );

        } catch (\Exception $e) {
            return Response::handler(
                500,
                'Gagal menghapus sub spektek',
                [],
                [],
                $e->getMessage()
            );
        }
    }
}
