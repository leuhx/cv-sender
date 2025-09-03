<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminFormController extends Controller
{
    /**
     * Display a listing of all forms (admin only).
     */
    public function index(Request $request)
    {
        $query = Form::with('user');

        // Filtro por usuário
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtro por posição
        if ($request->filled('position')) {
            $query->where('position', 'like', '%' . $request->position . '%');
        }

        // Filtro por educação
        if ($request->filled('education')) {
            $query->where('education', 'like', '%' . $request->education . '%');
        }

        $forms = $query->latest()->paginate(15)->appends($request->query());

        $users = User::applicants()->select('id', 'name', 'email')->get();

        return Inertia::render('Admin/Forms/Index', [
            'forms' => $forms,
            'users' => $users,
            'filters' => $request->only(['user_id', 'position', 'education']),
        ]);
    }

    /**
     * Display the specified form (admin only).
     */
    public function show(Form $form)
    {
        $form->load('user');

        return Inertia::render('Admin/Forms/Show', [
            'form' => $form,
        ]);
    }

    /**
     * Remove the specified form from storage (admin only).
     */
    public function destroy(Form $form)
    {
        // Deletar arquivo do CV se existir
        if ($form->cv_path) {
            Storage::disk('public')->delete($form->cv_path);
        }

        $form->delete();

        return redirect()->route('admin.forms.index')
            ->with('success', 'Formulário deletado com sucesso!');
    }

    /**
     * Download CV file (admin only).
     */
    public function downloadCv(Form $form)
    {
        if (!$form->cv_path || !Storage::disk('public')->exists($form->cv_path)) {
            abort(404, 'Arquivo de CV não encontrado.');
        }

        $filePath = Storage::disk('public')->path($form->cv_path);
        $fileName = $form->name . '_CV.' . pathinfo($form->cv_path, PATHINFO_EXTENSION);

        return response()->download($filePath, $fileName);
    }

    /**
     * Export forms data to CSV (admin only).
     */
    public function export(Request $request)
    {
        $query = Form::with('user');

        // Aplicar mesmos filtros do index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('position')) {
            $query->where('position', 'like', '%' . $request->position . '%');
        }

        if ($request->filled('education')) {
            $query->where('education', 'like', '%' . $request->education . '%');
        }

        $forms = $query->latest()->get();

        $filename = 'forms_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($forms) {
            $file = fopen('php://output', 'w');

            // Cabeçalhos CSV
            fputcsv($file, [
                'ID',
                'Nome do Candidato',
                'Email do Candidato',
                'Posição',
                'Educação',
                'Observações',
                'Data de Envio',
                'Nome do Usuário',
                'Email do Usuário'
            ]);

            // Dados
            foreach ($forms as $form) {
                fputcsv($file, [
                    $form->id,
                    $form->name,
                    $form->email,
                    $form->position,
                    $form->education,
                    $form->observations,
                    $form->created_at->format('Y-m-d H:i:s'),
                    $form->user->name ?? '',
                    $form->user->email ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
