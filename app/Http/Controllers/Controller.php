<?php

namespace App\Http\Controllers;

use App\Models\FamilyTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyTreeController extends Controller
{
    public function index()
    {
        // Дерева пользователя будут загружаться на dashboard
        return redirect()->route('dashboard');
    }

    public function create()
    {
        return view('family-trees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $tree = FamilyTree::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['tree_id' => $tree->id]);
        }

        return redirect()->route('family-trees.show', $tree)->with('success', 'Дерево успешно создано!');
    }

    public function show(FamilyTree $familyTree)
    {
        // Проверяем, что пользователь имеет доступ к этому дереву
        if ($familyTree->user_id !== Auth::id()) {
            abort(403);
        }

        $people = $familyTree->people; // Получаем всех людей в этом дереве

        return view('family-trees.show', compact('familyTree', 'people'));
    }

    public function destroy(FamilyTree $familyTree)
    {
        if ($familyTree->user_id !== Auth::id()) {
            abort(403);
        }

        $familyTree->delete();

        return redirect()->route('dashboard')->with('success', 'Дерево удалено.');
    }

    // Методы importGedcom и exportGedcom добавим позже
}