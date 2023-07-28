<html>
<head>
<title>Form Successfully Submitted</title>
<style>
.form-success {
  position: relative;
  width: 200px;
  height: 200px;
  background-color: green;
  color: white;
  font-size: 16px;
  text-align: center;
  padding: 10px;
  margin: 100px auto;
  border-radius: 5px;
  animation: success 1s ease-in-out;
}

@keyframes success {
  0% {
    opacity: 0;
    transform: scale(0);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.form-success button {
  background-color: blue;
  color: white;
  font-size: 16px;
  text-align: center;
  padding: 10px;
  margin-top: 10px;
  border-radius: 5px;
  cursor: pointer;
}
p{
    text-align: center;
}
button {
  display: block;
  margin: 0 auto;
}

</style>
</head>
<body>
<div class="form-success">
<h1>Form Successfully Submitted!</h1>
</div>
<p>Your form has been successfully submitted. We will get back to you as soon as possible.</p>
<button onclick="window.location.href='student form.php'"><b>Return</b> to the form</button>

</body>
</html>