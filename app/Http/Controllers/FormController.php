<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FormController extends Controller
{
    /**
     * Display a listing of user's forms (applicant only).
     */
    public function index()
    {
        $forms = Form::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return Inertia::render('Forms/Index', [
            'forms' => $forms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Forms/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validEducationLevels = [
            'Ensino Fundamental',
            'Ensino Médio',
            'Técnico em Informática',
            'Tecnólogo em Análise e Desenvolvimento de Sistemas',
            'Curso Superior de Tecnologia em Redes de Computadores',
            'Bacharelado em Ciência da Computação',
            'Bacharelado em Sistemas de Informação',
            'Bacharelado em Engenharia de Software',
            'Pós-graduação/Especialização',
            'Mestrado',
            'Doutorado'
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'education' => 'required|string|in:' . implode(',', $validEducationLevels),
            'observations' => 'nullable|string',
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:1024', // 1MB max
        ]);

        // Fazer upload do CV
        $cvPath = null;
        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('cvs', 'public');
        }

        Form::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'position' => $validated['position'],
            'education' => $validated['education'],
            'observations' => $validated['observations'] ?? null,
            'cv_path' => $cvPath,
        ]);

        return redirect()->route('forms.index')
            ->with('success', 'Formulário enviado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        // Verificar se o formulário pertence ao usuário logado
        if ($form->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para ver este formulário.');
        }

        return Inertia::render('Forms/Show', [
            'form' => $form,
            'userRole' => 'applicant',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        // Verificar se o formulário pertence ao usuário logado
        if ($form->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para editar este formulário.');
        }

        return Inertia::render('Forms/Edit', [
            'form' => $form,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $form)
    {
        $form = Form::findOrFail($form);

        // Verificar se o formulário pertence ao usuário logado
        if ($form->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para editar este formulário.');
        }

        $validEducationLevels = [
            'Ensino Fundamental',
            'Ensino Médio',
            'Técnico em Informática',
            'Tecnólogo em Análise e Desenvolvimento de Sistemas',
            'Curso Superior de Tecnologia em Redes de Computadores',
            'Bacharelado em Ciência da Computação',
            'Bacharelado em Sistemas de Informação',
            'Bacharelado em Engenharia de Software',
            'Pós-graduação/Especialização',
            'Mestrado',
            'Doutorado'
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'education' => 'required|string|in:' . implode(',', $validEducationLevels),
            'observations' => 'nullable|string',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:1024',
        ]);

        // Fazer upload do novo CV se fornecido
        if ($request->hasFile('cv_file')) {
            // Deletar CV antigo se existir
            if ($form->cv_path) {
                Storage::disk('public')->delete($form->cv_path);
            }

            $validated['cv_path'] = $request->file('cv_file')->store('cvs', 'public');
        }

        $form->update($validated);

        return redirect()->route('forms.index')
            ->with('success', 'Formulário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        // Verificar se o formulário pertence ao usuário logado
        if ($form->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para deletar este formulário.');
        }

        // Deletar arquivo do CV se existir
        if ($form->cv_path) {
            Storage::disk('public')->delete($form->cv_path);
        }

        $form->delete();

        return redirect()->route('forms.index')
            ->with('success', 'Formulário deletado com sucesso!');
    }
}
