<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Tips;
 
class TipsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tips = Tips::all();
        return response()->json($tips, 200);
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tips.create');
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $foto = $request->file("foto");

        if ($foto) {
            $fileName = time() . $foto->getClientOriginalName();
            $simpan = $foto->move(public_path('storage/images/'), $fileName);

            $store_tip = new Tips();
            $store_tip->foto = $fileName;
            $store_tip->nama = $request->input('nama');
            $store_tip->deskripsi = $request->input('deskripsi');

            $simpan = $store_tip->save();

            if ($simpan) {
                return response()->json(["message" => "successs add foto"], 201);
            } else {
                return response()->json(["message" => "Gagal"], 401);
            }
        } else {
            $f = $request->file('foto');
            // Tambahkan validasi jika foto tidak diunggah
            return response()->json(["message" => "Gagal, foto wajib diunggah $f"], 501);
        }
    }

 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    // $tips = Tips::findOrFail($id);
    // return view('tips.edit', compact('tips'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        $foto = $request->file("foto");

        $tipID = $request->input('id');
        $tip = Tips::find($tipID);
        $message = "Data berhasil di Update, Tanpa Foto";

        if ($foto) {
            $fileName = time() . $foto->getClientOriginalName();
            $simpan = $foto->move(public_path('storage/images/'), $fileName);
            $hapus = unlink(public_path('storage/images/').$tip->foto);
            $tip->foto = $fileName;
            $message = "Data berhasil di Update, Dengan Foto";
            
        }  

        $tip->nama = $request->input('nama');
        $tip->deskripsi = $request->input('deskripsi');

        $simpan = $tip->save();

        if($simpan){
            return response()->json(["message" => $message], 201);
        } else {
            return response()->json(["message" => "Gagal Memperbarui data"], 401);

        }
    }

 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $tips = Tips::findOrFail($id);
            $tips->delete();
    
            return response()->json(['message' => 'Data tips berhasil dihapus!'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Data tips tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data tips.'], 500);
        }
    }
    

}