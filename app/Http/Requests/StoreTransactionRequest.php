<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_id' => ['required', 'exists:accounts,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Custom message validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'account_id.required' => 'Akun bank atau dompet wajib dipilih.',
            'account_id.exists' => 'Akun bank yang dipilih tidak valid.',
            'category_id.required' => 'Kategori transaksi wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'type.required' => 'Jenis transaksi (Pemasukan/Pengeluaran) wajib dipilih.',
            'amount.required' => 'Nominal transaksi wajib diisi.',
            'amount.numeric' => 'Nominal harus berupa angka.',
            'amount.min' => 'Nominal transaksi minimal Rp 0.01.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
        ];
    }
}

