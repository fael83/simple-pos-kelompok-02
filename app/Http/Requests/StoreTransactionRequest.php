<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
        'items' => ['required', 'array', 'min:1'],
        'items.*.product_id' => ['required', 'exists:products,id'],
        'items.*.qty' => ['required', 'integer', 'min:1'],
        ];
    }
}