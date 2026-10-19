<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:bank,e-wallet,cash'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'initial_balance' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom error messages for attributes.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama akun bank atau dompet wajib diisi.',
            'type.required' => 'Jenis akun harus dipilih.',
            'type.in' => 'Jenis akun harus berupa Bank, E-Wallet, atau Tunai.',
            'initial_balance.required' => 'Saldo awal wajib diisi (minimal 0).',
            'initial_balance.numeric' => 'Saldo awal harus berupa angka.',
            'initial_balance.min' => 'Saldo awal tidak boleh bernilai negatif.',
        ];
    }
}

