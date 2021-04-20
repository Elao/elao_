export default `
.crash__symbol {
    fill: #7f1A55;
    font-family: 'faktum bold';
    font-size: 0.05em;
    animation: floating 1s linear infinite;
}

@keyframes floating {
  from {
    transform-origin: center;
    transform: rotate(0deg);
  }
  to {
    transform-origin: center;
    transform: rotate(360deg);
  }
}
`;
