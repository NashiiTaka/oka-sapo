import OpenAI from "openai";

// for(var i = 0;i < process.argv.length; i++){
//   console.log("argv[" + i + "] = " + process.argv[i]);
// }

// ChatGPT Assistantへの接続テスト
// https://platform.openai.com/docs/assistants/overview
const openai = new OpenAI();

// アシスタントを条件指定して取得する方法がわからなかったので、とりあえず0番目。
// nameはあったので、name指定とかで取得はできるが、まぁ増えたらでいいかな。
// const assistant = (await openai.beta.assistants.list()).data[0];

let message = process.argv[2];
let thread_id = process.argv[3];

// スレッドの作成
if (!thread_id) {
  const thread = await openai.beta.threads.create();
  thread_id = thread.id;
}

// メッセージの作成
const newMessage = await openai.beta.threads.messages.create(
  thread_id,
  {
    role: "user",
    content: message
  }
);

// アシスタントの実行
let run = await openai.beta.threads.runs.createAndPoll(
  thread_id,
  {
    assistant_id: "asst_HCwnSdMvqno8SX13Ya3Fjdjk",
  }
);

// 結果の取得
if (run.status === 'completed') {
  const messages = await openai.beta.threads.messages.list(
    run.thread_id
  );
  const message = messages.data[0];
  //res.json(message.content[0].text);
  let json = message.content[0].text;
  if (typeof (json) === 'object') {
    json = json.value ? json.value : json;
  }
  if (typeof (json) === 'string') {
    json = JSON.parse(json);
  }
  json.thread_id = run.thread_id;
  console.log(JSON.stringify(json));
} else {
  console.log(run.status);
}
