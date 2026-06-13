@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Live Chat Support</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <h6>Online Users</h6>
                <ul id="onlineUsers" class="list-unstyled mb-0"></ul>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-3">
                <div id="messages" style="height:300px; overflow:auto;" class="mb-3"></div>
                <div class="input-group">
                    <input id="msgInput" class="form-control" placeholder="Type message...">
                    <button id="sendBtn" class="btn btn-pink">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
<script>
const socket = io('http://localhost:4000');
socket.emit('join', { userId: '{{ auth()->id() ?? 0 }}', name: '{{ auth()->user()->name ?? "Guest" }}', role: '{{ auth()->user()->role ?? "user" }}' });
const messages = document.getElementById('messages');
const input = document.getElementById('msgInput');

document.getElementById('sendBtn').onclick = () => {
    if (!input.value.trim()) return;
    socket.emit('send-message', { sender: '{{ auth()->user()->name ?? "Guest" }}', text: input.value });
    input.value = '';
};

socket.on('receive-message', (msg) => {
    messages.innerHTML += `<div class="mb-2"><strong>${msg.sender}:</strong> ${msg.text}</div>`;
    messages.scrollTop = messages.scrollHeight;
});

socket.on('online-users', (list) => {
    document.getElementById('onlineUsers').innerHTML = list.map(u => `<li>${u.name} (${u.role})</li>`).join('');
});
</script>
@endsection
