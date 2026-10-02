@extends('layouts.print', [
    'title' => 'نوسراوی فەرمی - ' . $mail->mail_number,
    'printLabel' => 'چاپکردنی نوسراو (Print)',
])

@section('content')
    <x-print.letterhead :number="$mail->mail_number" :date="$mail->mail_date->format('Y / m / d')" />

    <x-print.addressee :to="$mail->sender_recipient" :subject="$mail->reason_subject" />

    <div class="letter-text">{{ $mail->letter_body ?: "سڵاو و ڕێز ...\nئاماژە بە بابەتەکە، داواکارین لە بەڕێزتان هاوکاری و کارئاسانی پێویستمان بۆ بکەن." }}</div>

    <x-print.signature :stamped="$mail->stamp_type === 'online'" />
@endsection
