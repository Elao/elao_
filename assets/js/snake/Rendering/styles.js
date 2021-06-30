export default `
#snake {
  width: 100vw;
  height: 100vh;
  position: fixed;
  zIndex: 1001;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #FF4344;
}

.crash__symbol {
    fill: #7f1A55;
    font-family: 'faktum bold';
    font-size: 0.05em;
    transform-origin: 1px 1px;
    animation: floating 2s linear infinite;
}

.crash__symbol:nth-child(5n+1) { animation-delay: -400ms; }
.crash__symbol:nth-child(5n+2) { animation-delay: -800ms; }
.crash__symbol:nth-child(5n+3) { animation-delay: -1200ms; }
.crash__symbol:nth-child(5n+4) { animation-delay: -1600ms; }

.shake {
  animation-name: shake;
  animation-duration: 600ms;
  transform-origin:50% 50%;
  animation-iteration-count: 1;
  animation-timing-function: linear;
}

@keyframes floating {
  0% {
    transform:  rotate3d(0, 0, 1, 0turn) scale3d(1, 1, 1);
  }
  50% {
    transform:  rotate3d(0, 0, 1, 0.5turn) scale3d(0.67, 0.67, 0.67);
  }
  100% {
    transform:  rotate3d(0, 0, 1, 1turn) scale3d(1, 1, 1);
  }
}

@-webkit-keyframes shake {
  0% { -webkit-transform: translate3d(2%, 1%, 0) rotate3d(0, 0, 1, 0deg); }
  10% { -webkit-transform: translate3d(-1%, -2%, 0) rotate3d(0, 0, 1, -1deg); }
  20% { -webkit-transform: translate3d(-2%, 0%, 0) rotate3d(0, 0, 1, 1deg); }
  40% { -webkit-transform: translate3d(1%, -1%, 0) rotate3d(0, 0, 1, -1.5deg); }
  80% { -webkit-transform: translate3d(-1%, -0.5%, 0) rotate3d(0, 0, 1, 1deg); }
  100% { -webkit-transform: translate3d(0, 0, 0) rotate3d(0, 0, 1, 0deg); }
}
`;
