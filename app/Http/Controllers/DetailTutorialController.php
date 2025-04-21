<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterTutorial;
use App\Models\DetailTutorial;
use Illuminate\Support\Facades\Storage;

class DetailTutorialController extends Controller
{
    /**
     * Store a newly created tutorial step in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterTutorial  $tutorial
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MasterTutorial $tutorial)
    {
        $validatedData = $request->validate([
            'order' => 'required|integer|min:1',
            'type' => 'required|in:text,image,code,url',
            'content' => 'required_unless:type,image',
            'image' => 'required_if:type,image|file|image|max:2048',
            'status' => 'nullable',
        ]);

        // Handle image upload
        if ($request->type == 'image' && $request->hasFile('image')) {
            $path = $request->file('image')->store('tutorial-images', 'public');
            $content = $path;
        } else {
            $content = $request->content;
        }

        // Create step with explicit master_tutorial_id
        $step = new DetailTutorial([
            'master_tutorial_id' => $tutorial->id,
            'order' => $request->order,
            'type' => $request->type,
            'content' => $content,
            'status' => $request->has('status') ? true : false,
        ]);

        $step->save();

        return redirect()->route('tutorials.show', $tutorial->id)
            ->with('success', 'Langkah berhasil ditambahkan!');
    }

    /**
     * Toggle the visibility status of a tutorial step.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetailTutorial  $step
     * @return \Illuminate\Http\Response
     */
    public function toggleVisibility(Request $request, DetailTutorial $step)
    {
        // Update status based on request or toggle current status
        $status = $request->has('status') ? $request->status : !$step->status;
        $step->status = $status;
        $step->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $step->status
            ]);
        }

        $tutorialId = $step->masterTutorial->id;
        return redirect()->route('tutorials.show', $tutorialId)
            ->with('success', 'Status langkah berhasil diperbarui!');
    }

    /**
     * Update the specified tutorial step in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetailTutorial  $step
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetailTutorial $step)
    {
        $validatedData = $request->validate([
            'order' => 'required|integer|min:1',
            'type' => 'required|in:text,image,code,url',
            'content' => 'required_unless:type,image',
            'image' => 'nullable|file|image|max:2048',
            'status' => 'nullable',
        ]);

        // Handle image upload if there's a new image
        if ($request->type == 'image' && $request->hasFile('image')) {
            // Delete the old image if it exists
            if ($step->type == 'image' && Storage::disk('public')->exists($step->content)) {
                Storage::disk('public')->delete($step->content);
            }
            $path = $request->file('image')->store('tutorial-images', 'public');
            $content = $path;
        } else if ($request->type != 'image') {
            $content = $request->content;
        } else {
            // Keep the existing image
            $content = $step->content;
        }

        $step->update([
            'order' => $request->order,
            'type' => $request->type,
            'content' => $content,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('tutorials.show', $step->masterTutorial->id)
            ->with('success', 'Langkah berhasil diperbarui!');
    }

    public function edit(DetailTutorial $step)
    {
        \Log::info('Edit method called for step ID: ' . $step->id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($step);
        }

        $tutorial = $step->masterTutorial;
        return view('tutorials.edit_step', compact('step', 'tutorial'));
    }

    /**
     * Remove the specified tutorial step from storage.
     *
     * @param  \App\Models\DetailTutorial  $step
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailTutorial $step)
{
    // Store the tutorial ID and step order before deletion
    $tutorialId = $step->masterTutorial->id;
    $deletedOrder = $step->order;

    // Delete the associated image if it exists
    if ($step->type == 'image' && Storage::disk('public')->exists($step->content)) {
        Storage::disk('public')->delete($step->content);
    }

    // Delete the step
    $step->delete();

    // Reorder all steps that come after the deleted step
    DetailTutorial::where('master_tutorial_id', $tutorialId)
        ->where('order', '>', $deletedOrder)
        ->orderBy('order')
        ->get()
        ->each(function ($subsequentStep, $index) use ($deletedOrder) {
            $subsequentStep->update(['order' => $deletedOrder + $index]);
        });

    return redirect()->route('tutorials.show', $tutorialId)
        ->with('success', 'Langkah berhasil dihapus!');
}
}
