async function loadMessages() {
  if (!activeFriendId) return;

  try {
    const res = await fetch(`api/load_messages.php?friend_id=${activeFriendId}`);
    const data = await res.json();

    if (!data.success) {
      console.warn("Failed to load messages:", data.message);
      return;
    }

    // Ensure both user_id and messages exist
    if (!data.user_id || !Array.isArray(data.messages)) return;

    chatMessages.innerHTML = "";

    data.messages.forEach(msg => {
      const div = document.createElement("div");

      // Compare numeric IDs (convert just in case)
      const isSentByUser = Number(msg.sender_id) === Number(data.user_id);

      div.classList.add("message", isSentByUser ? "sent" : "received");
      div.innerHTML = `
        ${msg.message}
        <span class="timestamp">
          ${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
        </span>
      `;
      chatMessages.appendChild(div);
    });

    // Smooth scroll to bottom after render
    chatMessages.scrollTo({
      top: chatMessages.scrollHeight,
      behavior: "smooth"
    });

  } catch (err) {
    console.error("Error loading messages:", err);
  }
}
