export default `
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


@keyframes floating {
  0% {
    transform: rotate(0deg) scale(1, 1);
  }
  50% {
    transform: rotate(180deg) scale(0.5, 0.5);
  }
  100% {
    transform: rotate(360deg) scale(1, 1);
  }
}
`;
